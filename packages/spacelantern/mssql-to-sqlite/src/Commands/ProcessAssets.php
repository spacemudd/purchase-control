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

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\PurchaseOrder;
use App\Models\ItemTemplate;
use App\Models\AssetStatus;
use App\Models\Item;
use Carbon\Carbon;

class ProcessAssets extends Command
{
    protected $signature = 'spacelantern:process-assets';

    protected $description = 'Process all assets';

    protected $loadedAssetStatuses = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->loadAssetStatusesIntoMemory();

        $this->process();

        $this->info('');
        $this->info('');

        $this->info('Assets: Completed');
    }

    /**
     *
     * @return void
     */
    public function loadAssetStatusesIntoMemory()
    {
        $this->loadedAssetStatuses = AssetStatus::get()->toArray();
    }

    /**
     *
     *
     * @return \Generator
     */
    public function getAllItems() {
        $step = 50;

        for ($offset = 0 ;; $offset += $step) {
            $q = DB::raw("
                SELECT dbo.Stock.*, 
                dbo.AssetStockStatus.StatusCode AS AssetStatusCode, 
                dbo.Asset.Code AS AssetCode,
                dbo.StockReceipt.PurchaseOrderNumber AS PurchaseOrderNumber
                 
                FROM dbo.Stock
                
                LEFT JOIN dbo.AssetStockStatus on dbo.Stock.AssetStatusId = dbo.AssetStockStatus.AssetStatusId
                
                LEFT JOIN dbo.Asset on dbo.Stock.AssetId = dbo.Asset.AssetId
                
                LEFT JOIN dbo.StockReceipt ON dbo.Stock.StockReceiptId = dbo.StockReceipt.StockReceiptId
                
                ORDER BY StockId OFFSET " . $offset . " ROWS FETCH NEXT " . $step . " ROWS ONLY
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

        Item::unguard();

        $counter = 0;

        foreach($this->getAllItems() as $foreignRecords) {

            foreach($foreignRecords as $foreignRecord) {

                $asset_status = $this->findAssetStatus($foreignRecord->AssetStatusCode);
                $asset_template = $this->findAssetTemplate($foreignRecord->AssetCode);

                if( ! empty($foreignRecord->PurchaseOrderNumber)) {
                    $purchase_order = $this->findPurchaseOrder($foreignRecord->PurchaseOrderNumber);

                    if($purchase_order) {
                        $purchase_order_id = $purchase_order->id;
                    } else {
                        \Log::warning([
                            'message' => 'Asset checked to have a PO but doesnt',
                            'asset' => $foreignRecord,
                        ]);
                        $purchase_order_id = null;
                    }
                } else {
                    \Log::warning([
                        'message' => 'Asset doesnt have a PO',
                        'asset' => $foreignRecord,
                    ]);

                    $purchase_order_id = null;
                }

                $asset = new Item();
                $asset->asset_template_id = $asset_template->id;
                $asset->purchase_order_id = $purchase_order_id;
                $asset->unit_price = $foreignRecord->Price;
                $asset->manufacturer_serial_number = $foreignRecord->ManufacturerSerialNumber;
                $asset->system_tag_number = empty($foreignRecord->SystemTagNumber) ? NULL : $foreignRecord->SystemTagNumber;
                $asset->finance_tag_number = empty($foreignRecord->FinanceTagNumber) ? NULL : $foreignRecord->FinanceTagNumber;
                $asset->warranty_expiry = Carbon::parse($foreignRecord->WarrantyExpiryDate);
                $asset->condition = 'good';
                $asset->replacement_asset_id = $foreignRecord->ReplacedStockId ? $this->findReplacementAsset($foreignRecord->ReplacedStockId)->id : null;
                $asset->asset_status_id = $asset_status['id'];
                $asset->active = true;
                $asset->remarks = $foreignRecord->Remarks;
                $asset->created_at = Carbon::parse($foreignRecord->CreationDate);
                $asset->updated_at = Carbon::parse($foreignRecord->ModificationDate);
                $asset->save();

                $this->info('Processed asset counter ID: ' . ++$counter );
            }
        }
    }

    public function findAssetStatus($foreignCode): array
    {
        $assetStatuses = $this->loadedAssetStatuses;

        foreach($assetStatuses as $status) {
            if($status['name'] === $foreignCode) {
                return $status;
            }
        }
    }

    public function findAssetTemplate($foreignCode): ItemTemplate
    {
        $assetTemplate = ItemTemplate::where('code', $foreignCode)->firstOrFail();

        return $assetTemplate;
    }

    public function findPurchaseOrder($purchaseOrderNumber)
    {
        $purchase_order = PurchaseOrder::where('order_number', $purchaseOrderNumber)->first();

        return $purchase_order;
    }

    /**
     * Queries the old database and then searches for the replaced asset ID
     * using its manufacturer's serial number.
     *
     * @param $foreignReplacedStockId
     * @return \App\Models\Item
     */
    public function findReplacementAsset($foreignReplacedStockId): Item
    {
        $foreignAsset = DB::connection('sqlsrv_old::read')->select('
          SELECT * FROM dbo.Stock WHERE dbo.Stock.StockId = ' . $foreignReplacedStockId . '
        ')[0];

        $asset = Item::where('manufacturer_serial_number', $foreignAsset->ManufacturerSerialNumber)
            ->firstOrFail();

        return $asset;
    }
}
