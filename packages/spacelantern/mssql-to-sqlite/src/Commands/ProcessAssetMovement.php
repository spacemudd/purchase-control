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

use App\Models\AssetMovement;
use App\Models\Item;
use App\Models\AssetStatus;
use App\Models\IssuedForList;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\ItemTemplate;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Vendor;
use Carbon\Carbon;

class ProcessAssetMovement extends Command
{
    protected $signature = 'spacelantern:process-asset-movement'; // Asset issuances

    protected $description = 'Process all asset movements';

    protected $departments = [];

    protected $assetStatuses = [];

    protected $issuedForList = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function loadDepartments()
    {
        $this->departments = Department::get()->toArray();
    }

    public function loadAssetStatus()
    {
        $this->assetStatuses = AssetStatus::get()->toArray();
    }

    public function loadIssuedForListIntoMemory()
    {
        $this->issuedForList = IssuedForList::get()->toArray();
    }

    public function handle()
    {
        $this->loadDepartments();

        $this->loadAssetStatus();

        $this->loadIssuedForListIntoMemory();

        $this->process();

        $this->info('');
        $this->info('');

        $this->info('Asset Movement: Completed');
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
                DISTINCT
                dbo.StockMovement.*, 
                dbo.Department.Code AS DepartmentCode,
                dbo.Employee.Code AS EmployeeCode,
                dbo.AssetStockStatus.StatusCode AS IssuedFor,
				dbo.Supplier.Code as SupplierCode,
				dbo.Stock.ManufacturerSerialNumber AS ManufacturerSerialNumber,
				dbo.AssetStockStatus.StatusCode AS CurrentStatus
                
                FROM dbo.StockMovement
                
                LEFT JOIN dbo.Department ON dbo.StockMovement.DepartmentId = dbo.Department.DepartmentId
                LEFT JOIN dbo.Employee ON dbo.StockMovement.EmployeeId = dbo.Employee.EmployeeId
                LEFT JOIN dbo.AssetStockStatus ON dbo.StockMovement.AssetStatusId = dbo.AssetStockStatus.AssetStatusId
				LEFT JOIN dbo.Supplier ON dbo.StockMovement.SupplierId = dbo.Supplier.SupplierId
				LEFT JOIN dbo.Stock ON dbo.StockMovement.StockId = dbo.Stock.StockId
				LEFT JOIN dbo.AssetStockStatus AS HELLO ON dbo.StockMovement.AssetStatusId = dbo.AssetStockStatus.AssetStatusId
            
                ORDER BY dbo.StockMovement.StockMovementId OFFSET " . $offset . " ROWS FETCH NEXT " . $step . " ROWS ONLY
            ");

            if (! $items = DB::connection('sqlsrv_old::read')->select($q)) {
                break;
            }

            yield $items;
        }
    }

    public function process()
    {
        DB::disableQueryLog();

        AssetMovement::unguard();

        $counter = 0;

        foreach($this->getAllItems() as $foreignRecords) {

            $recordsToInsert = [];

            foreach($foreignRecords as $foreignRecord) {

                $department = $this->findDepartment($foreignRecord->DepartmentCode);
                $employee = $this->findEmployee($foreignRecord->EmployeeCode);
                $issuedForId = $this->findIssuedForId($foreignRecord->IssuedFor);
                $vendor = $this->findSupplier($foreignRecord->SupplierCode);

                if(!$foreignRecord->ManufacturerSerialNumber) {
                    \Log::warning([
                        'message' => 'Asset wasnt found with a ManufacturerSerialNumber',
                        'data' => $foreignRecord,
                        ]);
                }

                $recordsToInsert[] = [
                    'asset_id' => $this->findAssetViaMsn($foreignRecord->ManufacturerSerialNumber),
                    'department_id' => $department->id,
                    'employee_id' => $employee->id,
                    'vendor_id' => $vendor->id,
                    'status_id' => $this->parseAssetStatusGetId($foreignRecord->CurrentStatus),
                    'issued_for_id' => $issuedForId,
                    'reference_number' => $foreignRecord->ReferenceNumber,
                    'date' => Carbon::parse($foreignRecord->MovementDate),
                    'action' => $foreignRecord->MovementAction,
                    'created_at' => Carbon::parse($foreignRecord->CreationDate),
                ];

                $this->info('Processing movement: ' . ++$counter );
            }

            AssetMovement::insert($recordsToInsert);
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

    public function findSupplier($foreignCode): Vendor
    {
        $vendor = Vendor::where('code', $foreignCode)->firstOrFail();

        return $vendor;
    }

    public function findIssuedForId($foreignIssuedFor)
    {
        foreach($this->issuedForList as $issuedFor) {
            if($issuedFor == $foreignIssuedFor) {
                return $issuedFor->id;
            }
        }

        return null;
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
        } else {
            \Log::warning('Asset not found with this MSN: ' . $msn);
        }
    }

    public function parseAssetStatusGetId($statusCode)
    {
        foreach($this->assetStatuses as $internalStatus) {
            if($internalStatus['name'] == $statusCode) {
                return $internalStatus['id'];
            }
        }

        return null;
    }
}
