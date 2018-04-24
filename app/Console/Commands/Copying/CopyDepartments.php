<?php

namespace App\Console\Commands\Copying;

use App\Models\Department;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyDepartments extends Command
{
    /**
     * @var string sqlsrv_itam | mysql_itam
     */
    private $foreignConnection = 'sqlsrv_itam';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clarimount:copy-departments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy departments from Clarimount ITAM system';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $currentProcessing = 0;

        DB::connection($this->foreignConnection)
            ->table('departments')
            ->orderBy('id')
            ->chunk(1000, function($departments) use ($currentProcessing) {

                $toInsert = [];
                foreach($departments as $department) {
                    $this->info(++$currentProcessing);

                    $region_id = $this->findRegionId($department);

                    $toInsert[] = [
                        'code' => $department->code,
                        'description' => $department->description,
                        'head_department' => $department->head_department,
                        'region_id' => $region_id,
                    ];
                }

                Department::insert($toInsert);
            });
    }

    /**
     *
     * @param $department
     * @return null | int
     */
    public function findRegionId($department)
    {
        $pivotTable = DB::connection($this->foreignConnection)
            ->table('department_region_pivot')
            ->where('department_id', $department->id)
            ->first();

        if( ! $pivotTable) {
            return null;
        }

        $foreignRegion = DB::connection($this->foreignConnection)
            ->table('regions')
            ->where('id', $pivotTable->region_id)
            ->first();

        if(!$foreignRegion) {
            return null;
        }

        $region = Region::firstOrCreate([
            'name' => $foreignRegion->name,
        ], [
            'name' => $foreignRegion->name,
        ]);

        return $region->id;
    }
}
