<?php
/**
 * FIRSTSOLUTIONS / CLARIMOUNT CONFIDENTIAL
 * __________________
 *
 * 2017 FirstSolutions Association (https://firstsolu.com)
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

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrdersItem;
use Illuminate\Console\Command;
use App\Models\PurchaseOrder;
use App\Models\ItemTemplate;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Vendor;
use Carbon\Carbon;

class ProcessPurchaseOrders extends Command
{
    protected $signature = 'spacelantern:process-purchase-orders'; // StockReceipt

    protected $description = 'Process all purchase-orders';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');
        $this->info('');

        $this->info('PurchaseOrders and items: Completed');
    }

    /**

        SELECT
        dbo.StockReceipt.*,
        dbo.Department.Code AS DepartmentCode,
        dbo.Employee.Code AS EmployeeCode,
        dbo.Supplier.Code AS SupplierCode

        FROM dbo.StockReceipt

        LEFT JOIN dbo.Department ON dbo.StockReceipt.DepartmentId = dbo.Department.DepartmentId

        LEFT JOIN dbo.Employee ON dbo.Employee.EmployeeId = dbo.Employee.EmployeeId

        LEFT JOIN dbo.Supplier ON dbo.Supplier.SupplierId = dbo.Supplier.SupplierId

        ORDER BY StockReceiptId OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY
     *
     *
     * @return \Generator
     */
    public function getAllItems() {
        $step = 50;

        for ($offset = 0 ;; $offset += $step) {

            $q = DB::raw("
                SELECT
                dbo.StockReceipt.*,
                dbo.Department.Code AS DepartmentCode,
                dbo.Employee.Code AS EmployeeCode,
                dbo.Supplier.Code AS SupplierCode
            
                FROM dbo.StockReceipt
            
                LEFT JOIN dbo.Department ON dbo.StockReceipt.DepartmentId = dbo.Department.DepartmentId
            
                LEFT JOIN dbo.Employee ON dbo.StockReceipt.EmployeeId = dbo.Employee.EmployeeId
            
                LEFT JOIN dbo.Supplier ON dbo.StockReceipt.SupplierId = dbo.Supplier.SupplierId
            
                ORDER BY StockReceiptId OFFSET " . $offset . " ROWS FETCH NEXT " . $step . " ROWS ONLY
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

        PurchaseOrder::unguard();
        PurchaseOrdersItem::unguard();

        $counter = 0;

        foreach($this->getAllItems() as $foreignRecords) {

            foreach($foreignRecords as $foreignRecord) {

                $department = $this->findDepartment($foreignRecord->DepartmentCode);
                $employee = $this->findEmployee($foreignRecord->EmployeeCode);
                $vendor = $this->findVendor($foreignRecord->SupplierCode);

                if ($foreignRecord->RecordStatus === 'Finalize') {
                    $status = 'committed';
                    $completed = true;
                } else {
                    $status = 'draft';
                    $completed = false;
                }

                $purchaseOrder = new PurchaseOrder();
                $purchaseOrder->department_id = $department->id;
                $purchaseOrder->employee_id = $employee->id;
                $purchaseOrder->vendor_id = $vendor->id;
                $purchaseOrder->main_order_number = $foreignRecord->MainOrderNumber;
                $purchaseOrder->order_number = $foreignRecord->PurchaseOrderNumber;
                $purchaseOrder->date = Carbon::parse($foreignRecord->PurchaseOrderDate);
                $purchaseOrder->delivery_number = $foreignRecord->SupplierDeliveryNumber;
                $purchaseOrder->delivery_date = Carbon::parse($foreignRecord->DeliveryDate);
                $purchaseOrder->status = $status;
                $purchaseOrder->completed = $completed;
                $purchaseOrder->created_at = Carbon::parse($foreignRecord->CreationDate);
                $purchaseOrder->updated_at = $foreignRecord->ModificationDate ? Carbon::parse($foreignRecord->ModificationDate) : Carbon::now();
                $purchaseOrder->created_by_id = 1;
                $purchaseOrder->created_by_raw = $foreignRecord->CreatedBy;
                $purchaseOrder->save();

                $poItemsQuery = DB::raw("
                        SELECT 
            
                        dbo.StockReceiptDetail.*,
                        dbo.Asset.Code as AssetTemplateCode,
                        dbo.AssetStockStatus.StatusCode as AssetStatusCode
                        
                        FROM dbo.StockReceiptDetail
                        
                        LEFT JOIN dbo.Asset ON dbo.StockReceiptDetail.AssetId = dbo.Asset.AssetId
                        LEFT JOIN dbo.AssetStockStatus ON dbo.StockReceiptDetail.AssetStatusId = dbo.AssetStockStatus.AssetStatusId
                        
                        WHERE StockReceiptId = " . $foreignRecord->StockReceiptId . "            
                ");

                $foreignPoItems = DB::connection('sqlsrv_old::read')->select($poItemsQuery);

                $recordsToInsert = [];

                foreach($foreignPoItems as $foreignPoItem) {
                    $recordsToInsert[] = [
                        'purchase_order_id' => $purchaseOrder->id,
                        'asset_template_id' => $this->findAssetTemplate($foreignPoItem->AssetTemplateCode)->id,
                        'manufacturer_serial_number' => $foreignPoItem->ManufacturerSerialNumber,
                        'system_tag_number' => $foreignPoItem->SystemTagNumber,
                        'finance_tag_number' => $foreignPoItem->FinanceTagNumber,
                        'unit_price' => $foreignPoItem->Price,
                        'warranty_expiry_date' => $this->calculateTheMessyExpiryDate($foreignPoItem),
                        'received_at' => Carbon::parse($foreignRecord->CreationDate),
                        'created_at' => Carbon::parse($foreignRecord->CreationDate),
                    ];
                }

                $recordsCollection = Collection::make($recordsToInsert)->chunk(10);

                foreach($recordsCollection as $chunk) {
                    PurchaseOrdersItem::insert($chunk->toArray());
                }

                $this->info('Processed purchase order counter ID: ' . ++$counter . ' & items (' . count($recordsToInsert) . ')');
            }
        }
    }

    public function findDepartment($foreignCode): Department
    {
        $department = Department::where('code', $foreignCode)->firstOrFail();

        return $department;
    }

    public function findEmployee($foreignCode): Employee
    {
        try {
            $employee = Employee::where('code', $foreignCode)->firstOrFail();
        } catch(\Exception $error) {
            dd($foreignCode);
            throw new \Exception('Employee code: ' . $foreignCode . ' wasnt found.');
        }

        return $employee;
    }

    public function findVendor($foreignCode): Vendor
    {
        $vendor = Vendor::where('code', $foreignCode)->firstOrFail();

        return $vendor;
    }

    public function findAssetTemplate(string $assetTemplateCode): ItemTemplate
    {
        $assetTemplate = ItemTemplate::where('code', $assetTemplateCode)->firstOrFail();

        return $assetTemplate;
    }

    public function calculateTheMessyExpiryDate($foreignPoItem): Carbon
    {
        $unit = $foreignPoItem->WarrantyExpiryUnit;
        $created_at = $foreignPoItem->CreationDate;
        $warrantyDuration = $foreignPoItem->Warranty;

        if($unit === 'Year') {
            
            $expiryDate = Carbon::parse($created_at)->addYears($warrantyDuration);

        } elseif($unit === 'Month') {

            $expiryDate = Carbon::parse($foreignPoItem->CreationDate)->addMonths($warrantyDuration);

        } else {

            throw new \Exception('Unknown warranty expiry unit: ' . $unit);

        }

        return $expiryDate;
    }
}
