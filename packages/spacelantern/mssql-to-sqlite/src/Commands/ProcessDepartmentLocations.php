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
use App\Models\DepartmentLocation;
use Carbon\Carbon;
use DB;

class ProcessDepartmentLocations extends Command
{
    protected $signature = 'spacelantern:process-department-locations';

    protected $description = 'Process all department locations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');

        $this->info('Department Locations: Completed');
    }

    public function process()
    {
        $foreignLocations = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.Location');

        $progressBar = $this->output->createProgressBar(count($foreignLocations));

        DepartmentLocation::unguard();

        foreach($foreignLocations as $foreignLocation) {
            $location = new DepartmentLocation();
            $location->code = $foreignLocation->Code;
            $location->active = $foreignLocation->ActiveStatus;
            $location->created_at = Carbon::parse($foreignLocation->CreationDate);
            $location->updated_at = Carbon::now();
            $location->save();

            $progressBar->advance();
        }

        $progressBar->finish();
    }
}
