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

use Illuminate\Console\Command;
use App\Models\Region;
use Carbon\Carbon;
use DB;

class ProcessRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spacelantern:process-regions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the Microsoft SQL database tables';

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
     * @return void
     */
    public function handle()
    {
        $this->process();

        $this->info('');

        $this->info('Regions: Completed');
    }

    public function process()
    {
        $foreignData = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.Region');

        $progressBar = $this->output->createProgressBar(count($foreignData));

        Region::unguard();

        $data = [];
        foreach($foreignData as $record) {
            $data[] = [
                'title' => $record->Code,
                'active' => $record->ActiveStatus,
                'created_at' => Carbon::parse($record->CreationDate),
                'updated_at' => Carbon::now(),
            ];

            $progressBar->advance();
        }

        Region::insert($data);

        $progressBar->finish();
    }
}
