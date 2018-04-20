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
use Illuminate\Console\Command;
use App\Models\ItemTemplate;
use App\Models\ItemCategory;
use App\Models\Manufacturer;
use App\Models\ItemType;
use Carbon\Carbon;

class ProcessAssetTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spacelantern:process-asset-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all asset templates with asset types and categories';

    private $manufacturers = [];
    private $assetTypes = [];
    private $assetCategories = [];

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->process();

        $this->info('');
        $this->info('');

        $this->info('Asset templates: Completed');
    }

    public function process()
    {
        $this->processAssetTypes();
        $this->processAssetCategories();
        $this->processAssetSubCategories();
        $this->processAssetTemplates();
    }

    public function processAssetTypes()
    {
        $this->info('');
        $this->info('');
        $this->info('----------');
        $this->info('Starting processing asset types');
        $this->info('----------');
        $this->info('');
        $this->info('');

        $foreignTypes = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.AssetType');

        $progressBar = $this->output->createProgressBar(count($foreignTypes));

        foreach($foreignTypes as $type) {
            $newType = new ItemType();
            $newType->name = $type->Code;
            $newType->created_at = Carbon::parse($type->CreationDate);
            $newType->save();

            $progressBar->advance();
        }

        $progressBar->finish();
    }

    public function processAssetCategories()
    {
        $this->info('');
        $this->info('');
        $this->info('----------');
        $this->info('Starting processing asset categories');
        $this->info('----------');
        $this->info('');
        $this->info('');

        $foreignTypes = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.AssetCategory');
        $progressBar = $this->output->createProgressBar(count($foreignTypes));

        foreach($foreignTypes as $type) {
            $category = ItemCategory::where('title', $type->Code)->exists();

            if(!$category) {
                $this->info('');
                $this->error('"' . $type->Code . '" does NOT exist in the local Sqlite database.');
                $this->info('');
                throw new \Exception('Category doesnt exist');
            }

            $progressBar->advance();
        }

        $progressBar->finish();
    }

    public function processAssetSubCategories()
    {
        $this->info('');
        $this->info('');
        $this->info('----------');
        $this->info('Starting processing asset sub categories');
        $this->info('----------');
        $this->info('');
        $this->info('');

        $foreignTypes = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.AssetSubCategory');
        $progressBar = $this->output->createProgressBar(count($foreignTypes));

        foreach($foreignTypes as $type) {
            $category = ItemCategory::where('title', $type->Code)->exists();

            if(!$category) {
                $this->error('"' . $type->Code . '" does NOT exist in the local Sqlite database.');
                throw new \Exception('Category doesnt exist');
            }

            $progressBar->advance();
        }

        $progressBar->finish();
    }


    public function processAssetTemplates()
    {
        $this->info('');
        $this->info('');
        $this->info('----------');
        $this->info('Processing asset templates');
        $this->info('----------');
        $this->info('');
        $this->info('');

        $this->loadAssetTemplateCategoriesAndTypesIntoMemory();

        // 1640~ rows.
        $foreignSql = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.Asset');

        $totalRows = DB::connection('sqlsrv_old::read')->select('SELECT COUNT(*) as rows FROM dbo.Asset')[0]->rows;
        $progressBar = $this->output->createProgressBar($totalRows);

        ItemTemplate::unguard();

        $foreignData = Collection::make($foreignSql);

        $foreignDataChunks = $foreignData->chunk(50);

        /**
         * Begin inserting into the DB in 100 records chunks.
         *
         */
        foreach($foreignDataChunks as $chunk => $records) {

            $recordsToInsert = [];

            foreach($records as $record) {
                /**
                 * For each record, we must find its appropriate manufacturer,
                 * and appropriate asset type and category.
                 *
                 */
                $recordsToInsert[] = $this->performAssetTemplateParsing($record);
            }

            ItemTemplate::insert($recordsToInsert);

            $progressBar->advance(50);
        }

        $progressBar->finish();
    }

    public function loadAssetTemplateCategoriesAndTypesIntoMemory()
    {
        $this->manufacturers = Collection::make(Manufacturer::get()->toArray());
        $this->assetTypes = Collection::make(ItemType::get()->toArray());
        $this->assetCategories = Collection::make(ItemCategory::get()->toArray());
    }

    public function performAssetTemplateParsing($foreignRecord)
    {
        $manufacturer = $this->findManufacturer($foreignRecord->ManufacturerId);
        $assetType = $this->findAssetType($foreignRecord->AssetTypeId);

        if($foreignRecord->SubCategoryId) {
            $assetCategory = $this->findAssetCategoryFromSubCategoryId($foreignRecord->SubCategoryId);
        } else {
            $assetCategory = $this->findAssetCategoryFromCategoryId($foreignRecord->CategoryId);
        }

        return [
            'code' => $foreignRecord->Code,
            'manufacturer_id' => $manufacturer['id'],
            'type_id' => $assetType['id'],
            'category_id' => $assetCategory['id'],
            'description' => $foreignRecord->Description,
            'unit_price' => $foreignRecord->UnitPrice,
            'details' => $foreignRecord->ModelDetail,
            'active' => $foreignRecord->ActiveStatus,
            'created_at' => Carbon::parse($foreignRecord->CreationDate),
            'updated_at' => Carbon::parse($foreignRecord->ModificationDate),
        ];
    }

    public function findManufacturer(int $foreignManufacturerId)
    {
        $foreignManufacturer = DB::connection('sqlsrv_old::read')->select("SELECT * FROM dbo.Manufacturer WHERE ManufacturerId = ?", [
            $foreignManufacturerId,
        ]);

        if(!count($foreignManufacturer)) {
            throw new \Exception('No manufacturers were found on the foreign SQL');
        } else {
            $foreignManufacturer = $foreignManufacturer[0];
        }


        $manufacturerArrayKey = $this->manufacturers->search(function($manufacturer) use ($foreignManufacturer) {
            return $manufacturer['code'] == $foreignManufacturer->Code;
        });

        return $this->manufacturers->get($manufacturerArrayKey);
    }

    public function findAssetType(int $assetTypeId)
    {
        $foreignAssetType = DB::connection('sqlsrv_old::read')->select("SELECT * FROM dbo.AssetType WHERE TypeId = ?", [
            $assetTypeId,
        ])[0];

        $assetTypeArrayKey = $this->assetTypes->search(function($assetType) use ($foreignAssetType) {
            return $assetType['name'] == $foreignAssetType->Code;
        });

        return $this->assetTypes->get($assetTypeArrayKey);
    }

    public function findAssetCategoryFromSubCategoryId(int $foreignSubCategoryId)
    {
        $foreignSubCategory = DB::connection('sqlsrv_old::read')->select("SELECT * FROM dbo.AssetSubCategory WHERE SubCategoryId = ?", [
            $foreignSubCategoryId,
        ])[0];

        $assetSubCategoryArrayKey = $this->assetCategories->search(function($assetCategory) use ($foreignSubCategory) {
            return $assetCategory['title'] == $foreignSubCategory->Code;
        });

        return $this->assetCategories->get($assetSubCategoryArrayKey);
    }

    public function findAssetCategoryFromCategoryId(int $foreignCategoryId)
    {
        $foreignCategory = DB::connection('sqlsrv_old::read')->select("SELECT * FROM dbo.AssetCategory WHERE CategoryId = ?", [
            $foreignCategoryId,
        ])[0];

        $assetCategoryArrayKey = $this->assetCategories->search(function($assetCategory) use ($foreignCategory) {
            return $assetCategory['title'] == $foreignCategory->Code;
        });

        return $this->assetCategories->get($assetCategoryArrayKey);
    }
}
