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

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\Vendor;

class ProcessVendors extends Command
{
    protected $signature = 'spacelantern:process-vendors';

    protected $description = 'Process all vendors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');

        $this->info('Vendors: Completed');
    }

    public function process()
    {
        $foreignSql = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.Supplier');

        $progressBar = $this->output->createProgressBar(count($foreignSql));

        Vendor::unguard();

        $foreignData = Collection::make($foreignSql);

        $foreignDataChunks = $foreignData->chunk(5);

        foreach($foreignDataChunks as $chunk => $records) {

            $recordsToInsert = [];

            foreach($records as $record) {
                $recordsToInsert[] = $this->performParsing($record);
            }

            Vendor::insert($recordsToInsert);
            $progressBar->advance(5);
        }

        $progressBar->finish();
    }

    public function performParsing($record)
    {
        return [
            'code' => $record->Code,
            'description' => $record->Description,
            'contact_person' => $record->ContactPerson,
            'contact_details' => $record->ContactDetail,
            'email' => $record->Email,
            'website' => $record->Website,
            'address' => $record->Address,
            'active' => $record->ActiveStatus,
            'created_at' => Carbon::parse($record->CreationDate),
            'updated_at' => Carbon::parse($record->ModificationDate),
        ];
    }
}
