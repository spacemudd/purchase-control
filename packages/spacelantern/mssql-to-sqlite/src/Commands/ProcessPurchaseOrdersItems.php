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

class ProcessPurchaseOrdersItems extends Command
{
    protected $signature = 'spacelantern:process-purchase-orders-items'; // StockReceiptDetail

    protected $description = 'Process all purchase-orders items';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');
        $this->info('');

        $this->info('PurchaseOrdersItems: Completed');
    }

    /**
     *
     * @return \Generator
     */
    public function getAllItems() {
        $step = 50;

        for ($offset = 0 ;; $offset += $step) {

            $q = DB::raw("
                SELECT dbo.StockReceiptDetail.*, dbo.Asset.Code AS AssetCode FROM dbo.StockReceiptDetail
                
                LEFT JOIN dbo.Asset on dbo.StockReceiptDetail.AssetId = dbo.Asset.AssetId
                
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

        PurchaseOrdersItem::unguard();

        $counter = 0;

        foreach($this->getAllItems() as $foreignRecords) {

            $recordsToInsert = [];

            foreach($foreignRecords as $foreignRecord) {

                $purchaseOrder = $this->findInternalPurchaseOrder($foreignRecord->StockReceiptId);
                $assetTemplate = $this->findAssetTemplate($foreignRecord->AssetCode);

                $poItem = new PurchaseOrdersItem();
                $poItem->purchase_order_id = $purchaseOrder->id;
                $poItem->asset_template_id = $assetTemplate->id;
                $poItem->manufacturer_serial_number = $foreignRecord->ManufacturerSerialNumber;
                $poItem->system_tag_number = $foreignRecord->SystemTagNumber;
                $poItem->finance_tag_number = $foreignRecord->FinanceTagNumber;
                $poItem->unit_price = $foreignRecord->Price;
                $poItem->warranty_expiry_date = $this->calculateTheMessyStuff($foreignRecord);
                $poItem->created_at = Carbon::parse($foreignRecord->CreationDate);
                $poItem->save();

                $this->info('Processed purchase order items counter ID: ' . ++$counter );
            }

            PurchaseOrdersItem::insert($recordsToInsert);
        }
    }

    public function findInternalPurchaseOrder($foreignStockReceiptId): PurchaseOrder
    {
        $purchase_order = PurchaseOrder::where('foreignStockReceiptId', $foreignStockReceiptId)->get()->first();

        return $purchase_order;
    }

    public function findAssetTemplate($assetCode): ItemTemplate
    {
        $assetTemplate = ItemTemplate::where('code', $assetCode)->firstOrFail();

        return $assetTemplate;
    }

    public function calculateTheMessyStuff($foreignPoItem): Carbon
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
