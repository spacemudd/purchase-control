<?php

namespace App\Console\Commands\Copying;

use App\Models\Department;
use App\Models\Employee;
use App\Models\StaffType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clarimount:copy-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy employees from Clarimount ITAM system';

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
        DB::connection('mysql_itam')
            ->table('employees')
            ->orderBy('id')
            ->chunk(1000, function($employees) {

                $toInsert = [];
                foreach($employees as $employee) {

                    $foreignDepartment = DB::connection('sqlsrv_itam')
                        ->table('departments')
                        ->where('id', $employee->department_id)
                        ->first();

                    $localDepartment = Department::where('code', $foreignDepartment->code)->firstOrFail();

                    $foreignStaffType = DB::connection('sqlsrv_itam')
                        ->table('staff_types')
                        ->where('id', $employee->staff_type_id)
                        ->first();

                    $localStaffType = StaffType::firstOrCreate([
                        'name' => $foreignStaffType->title,
                    ], [
                        'name' => $foreignStaffType->title,
                    ]);

                    $toInsert[] = [
                        'code' => $employee->code,
                        'department_id' => $foreignDepartment->id,
                        'staff_type_id' => $localStaffType->id,
                        'name' => $localStaffType->name,
                        'email' => $localStaffType->email,
                        'phone' => $localStaffType->phone,
                    ];
                }

                $this->info('Inserted 1000');

                Employee::insert($toInsert);
            });
    }
}
