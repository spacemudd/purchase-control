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

namespace Spacelantern\MssqlToSqlite\Commands\Copying;

use Illuminate\Console\Command;
use App\Models\Region;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class CopyEverything extends Command
{
    protected $signature = 'spacelantern:copy-everything';

    protected $description = 'List the Microsoft SQL database tables';

    protected $tablesToCopy = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->tablesToCopy = [
            'Manufacturer' => 'ManufacturerId',
            'AssetType' => 'TypeId',
            'AssetCategory' => 'CategoryId',
            'AssetSubCategory' => 'SubCategoryId',
            'Asset' => 'AssetId',
            'Region' => 'RegionId',
            'Location' => 'LocationId',
            'StaffType' => 'StaffTypeId',
            'Department' => 'DepartmentId',
            'Employee' => 'EmployeeId',
            'Supplier' => 'SupplierId',
            'ReferenceNumberType' => 'ReferenceTypeId',
            'AssetStockStatus' => 'AssetStatusId',
            'AssetRepairStatus' => 'StatusCode',
            'AssetReturnStatus' => 'StatusCode',
            'StockReceipt' => 'StockReceiptId',
            'StockReceiptDetail' => 'StockReceiptDetailId',
            'Stock' => 'StockId',
            'StockIssuance' => 'StockIssuanceId',
            'StockIssuanceDetail' => 'StockIssuanceId',
            'StockMovement' => 'StockMovementId',
        ];

        $this->process();
    }

    public function getRecords(string $tableName, string $orderByColumn) {
        $step = 100;

        for ($offset = 0 ;; $offset += $step) {
            $q = DB::raw('SELECT * FROM dbo.' . $tableName . ' ORDER BY ' . $orderByColumn  . ' OFFSET '. $offset .' ROWS FETCH NEXT '. $step . ' ROWS ONLY');

            if (! $items = DB::connection('sqlsrv_old::read')->select($q)) {
                break;
            }

            yield $items;
        }
    }

    public function process()
    {
        foreach($this->tablesToCopy as $table => $orderByColumn) {

            $tableRecordsCounter = 0;
            foreach($this->getRecords($table, $orderByColumn) as $foreignRecords) {

                $data = Collection::make(collect($foreignRecords)->map(function($x){ return (array) $x; })->toArray());

                $data->chunk(50)->each(function($insertData) use ($table) {
                    DB::table('old_' . $table)->insert($insertData->toArray());
                });

                $this->info('');
                $this->info('old_' . $table . ': copying in progress. Counter: ' . $tableRecordsCounter);

                $tableRecordsCounter += 100;
            }

        }
    }
}
