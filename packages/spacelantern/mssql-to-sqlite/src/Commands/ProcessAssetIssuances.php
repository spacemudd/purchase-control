<?php
/**
 * FIRSTSOLUTIONS / CLARIMOUNT CONFIDENTIAL
 * __________________
 *
 * 2018 FirstSolutions Association (https://firstsolu.com)
 * All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of FirstSolutions Association and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to FirstSolutions Associations
 * and its suppliers and may be covered by U.S. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from FirstSolutions Association.
 */

namespace Spacelantern\MssqlToSqlite\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\AssetIssuanceItem;
use Illuminate\Console\Command;
use App\Models\AssetIssuance;
use App\Models\IssuedForList;
use App\Models\ReferenceType;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Vendor;
use App\Models\Item;
use Carbon\Carbon;

class ProcessAssetIssuances extends Command
{
    protected $signature = 'spacelantern:process-asset-issuances'; // Asset issuances

    protected $description = 'Process all asset issuances';

    protected $referenceTypes = [];

    protected $issuedForList = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function loadReferenceTypesIntoMemory()
    {
        $this->referenceTypes = ReferenceType::get()->toArray();
    }

    public function loadIssuedForListIntoMemory()
    {
        $this->issuedForList = IssuedForList::get()->toArray();
    }

    public function handle()
    {
        $this->loadReferenceTypesIntoMemory();
        $this->loadIssuedForListIntoMemory();

        $this->process();

        $this->info('');
        $this->info('');

        $this->info('Asset Issuances: Completed');
    }

    public function process()
    {
        DB::disableQueryLog();

        AssetIssuance::unguard();
        AssetIssuanceItem::unguard();

        $counter = 0;

        foreach($this->getAllItems() as $foreignRecords) {

            foreach($foreignRecords as $foreignRecord) {

                $department = $this->findDepartment($foreignRecord->DepartmentCode);
                $employee = $this->findEmployee($foreignRecord->EmployeeCode);
                $referenceNumberId = $this->findReferenceNumber($foreignRecord->ReferenceNumberTypeName);

                $issuance = new AssetIssuance();
                $issuance->department_id = $department->id;
                $issuance->employee_id = $employee->id;
                $issuance->ref_id = $foreignRecord->IssuanceNumber;
                $issuance->prefix = 'ISSUANCE';
                $issuance->created_by = 1;
                $issuance->reference_type_id = $referenceNumberId;
                $issuance->reference_number = $foreignRecord->ReferenceNumber;
                $issuance->issuance_date = Carbon::parse($foreignRecord->IssuanceDate);
                $issuance->status = $foreignRecord->RecordStatus == 'Issued' ? 'committed' : 'draft';
                $issuance->created_at = Carbon::parse($foreignRecord->CreationDate);
                $issuance->updated_at = $foreignRecord->ModificationDate ? Carbon::parse($foreignRecord->ModificationDate) : null;
                $issuance->created_by_raw = $foreignRecord->CreatedBy;
                $issuance->save();

                // We take in all the asset issuances' items.
                $this->processForeignIssuanceItems($foreignRecord, $issuance);

                $this->info('Processed asset issuance (w/ items) counter: ' . ++$counter );
            }
        }
    }

    /**
     *
     * @return \Generator
     */
    public function getAllItems() {
        $step = 50;

        for ($offset = 0 ;; $offset += $step) {

            $q = DB::raw("
                SELECT 
                
                dbo.StockIssuance.*, 
                dbo.Department.Code AS DepartmentCode,
                dbo.Employee.Code AS EmployeeCode,
                dbo.ReferenceNumberType.StatusCode AS ReferenceNumberTypeName,
                dbo.AssetStockStatus.StatusCode AS IssuedFor
                
                FROM dbo.StockIssuance
                
                LEFT JOIN dbo.Department ON dbo.StockIssuance.DepartmentId = dbo.Department.DepartmentId
                LEFT JOIN dbo.Employee ON dbo.StockIssuance.EmployeeId = dbo.Employee.EmployeeId
                LEFT JOIN dbo.ReferenceNumberType ON dbo.StockIssuance.ReferenceTypeId = dbo.ReferenceNumberType.ReferenceTypeId
                LEFT JOIN dbo.AssetStockStatus ON dbo.StockIssuance.AssetStatusId = dbo.AssetStockStatus.AssetStatusId
            
                ORDER BY StockIssuanceId OFFSET " . $offset . " ROWS FETCH NEXT " . $step . " ROWS ONLY
            ");

            if (! $items = DB::connection('sqlsrv_old::read')->select($q)) {
                break;
            }

            yield $items;
        }
    }

    public function processForeignIssuanceItems($foreignRecord, AssetIssuance $issuance)
    {
        $foreignIssuanceItems = DB::connection('sqlsrv_old::read')->select("
                    SELECT 
    
                    dbo.StockIssuanceDetail.*,
                    dbo.Stock.ManufacturerSerialNumber AS ManufacturerSerialNumber
                    
                    FROM dbo.StockIssuanceDetail
                    
                    LEFT JOIN dbo.Stock ON dbo.StockIssuanceDetail.StockId = dbo.Stock.StockId
                    
                    WHERE dbo.StockIssuanceDetail.StockIssuanceId = " . $foreignRecord->StockIssuanceId . "
                ");

        $issuanceItemsToInsert = [];

        foreach($foreignIssuanceItems as $item) {

            $issuedForId = null;

            foreach($this->issuedForList as $issuedFor) {
                if($issuedFor['name'] === $foreignRecord->IssuedFor) {
                    $issuedForId = $issuedFor['id'];
                }
            }

            // If the issued for ID is still null, log the string.
            if( ! $issuedForId) {
                Log::warning([
                    'message' => 'Cant find the issued for status',
                    'data' => $foreignRecord,
                ]);
            }

            $issuanceItemsToInsert[] = [
                'asset_id' => $this->findAssetViaMsn($item->ManufacturerSerialNumber),
                'asset_issuance_id' => $issuance->id,
                'issued_for_id' => $issuedForId,
                'created_at' => Carbon::parse($item->CreationDate),
            ];
        }

        $recordsCollection = Collection::make($issuanceItemsToInsert)->chunk(10);

        foreach($recordsCollection as $chunk) {
            AssetIssuanceItem::insert($chunk->toArray());
        }
    }

    public function findReferenceNumber($code)
    {
        foreach($this->referenceTypes as $reference) {
            if($reference['name'] == $code) {
                return $reference['id'];
            }
        }

        return null;
    }

    public function findDepartment($foreignCode): Department
    {
        $department = Department::where('code', $foreignCode)->firstOrFail();

        return $department;
    }

    public function findEmployee($foreignCode): Employee
    {
        $employee = Employee::where('code', $foreignCode)->firstOrFail();

        return $employee;
    }

    public function findVendor($foreignCode): Vendor
    {
        $vendor = Vendor::where('code', $foreignCode)->firstOrFail();

        return $vendor;
    }

    public function findAssetViaMsn($msn)
    {
        $asset = Item::where('manufacturer_serial_number', $msn)->first();

        if($asset) {
            return $asset->id;
        }

        // TODO: Make an asset called A100 and that will be an 'empty' MSN.
    }
}
