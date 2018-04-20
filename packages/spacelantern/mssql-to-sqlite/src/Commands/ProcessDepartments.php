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

use App\Models\DepartmentGroup;
use App\Models\DepartmentLocation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Region;
use Carbon\Carbon;

class ProcessDepartments extends Command
{
    protected $signature = 'spacelantern:process-departments';

    protected $description = 'Process all departments';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');

        $this->info('Departments: Completed');

    }

    public function getAllItems() {
      $step = 100;

      for ($offset = 0 ;; $offset += $step) {
        $q = DB::raw('SELECT * FROM dbo.Department ORDER BY DepartmentId OFFSET '. $offset .' ROWS FETCH NEXT '. $step . ' ROWS ONLY');

        if (! $items = DB::connection('sqlsrv_old::read')->select($q)) {
          break;
        }

        yield $items;
      }
    }

    public function process()
    {
        DB::disableQueryLog();

        $totalItems = DB::connection('sqlsrv_old::read')->select('SELECT COUNT(*) as rows FROM dbo.Department')[0]->rows;

        $progressBar = $this->output->createProgressBar($totalItems);

        Department::unguard();

        foreach($this->getAllItems() as $foreignRecords) {

            foreach($foreignRecords as $foreignRecord) {
                $department = new Department();
                $department->code = $foreignRecord->Code;
                $department->description = $foreignRecord->Description;
                $department->head_department = $foreignRecord->HeadOfDepartment;
                $department->active = $foreignRecord->ActiveStatus;
                $department->created_at = Carbon::parse($foreignRecord->CreationDate);
                $department->updated_at = Carbon::parse($foreignRecord->ModificationDate);
                $department->save();

                if($foreignRecord->RegionId) {
                    $this->attachRegion($department, $foreignRecord->RegionId);
                }

                if($foreignRecord->LocationId) {
                    $this->attachLocation($department, $foreignRecord->LocationId);
                }
            }

            $progressBar->advance(100);
        }

        $progressBar->finish();
    }

    public function attachRegion(Department $department, int $foreignRegionId): Region
    {
        $foreignRegion = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.Region WHERE RegionId = ?', [
            $foreignRegionId,
        ]);

        $foreignRegionTitle = $foreignRegion[0]->Code;

        $region = Region::where('title', $foreignRegionTitle)->firstOrFail();

        $department->regions()->attach($region);

        return $region;
    }

    /**
     * Attach locations to the department.
     * Note that there are sometimes no
     * locations to the department.
     *
     * @param \App\Models\Department $department
     * @param int $foreignLocationId
     * @return \App\Models\DepartmentLocation
     */
    public function attachLocation(Department $department, int $foreignLocationId)
    {
        $foreignLocation = DB::connection('sqlsrv_old::read')->select('SELECT * from dbo.Location WHERE LocationId = ?', [
            $foreignLocationId,
        ]);

        if(count($foreignLocation)) {
            $foreignLocationCode = $foreignLocation[0]->Code;

            $location = DepartmentLocation::where('code', $foreignLocationCode)->firstOrFail();

            $department->locations()->attach($location);

            return $location;
        }
    }
}
