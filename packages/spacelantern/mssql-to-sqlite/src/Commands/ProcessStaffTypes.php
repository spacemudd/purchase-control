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
use App\Models\StaffType;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class ProcessStaffTypes extends Command
{
    protected $signature = 'spacelantern:process-staff-types';

    protected $description = 'List the Microsoft SQL database tables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');

        $this->info('Staff Types: Completed');
    }

    public function process()
    {
        $foreignTypes = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.StaffType');

        $progressBar = $this->output->createProgressBar(count($foreignTypes));

        StaffType::unguard();

        $types = [];
        foreach($foreignTypes as $foreignType) {
            $types[] = [
                'title' => $foreignType->Code,
                'slug' => str_slug($foreignType->Code),
                'active' => $foreignType->ActiveStatus,
                'created_at' => Carbon::parse($foreignType->CreationDate),
                'updated_at' => Carbon::now(),
            ];

            $progressBar->advance();
        }

        StaffType::insert($types);

        $progressBar->finish();
    }
}
