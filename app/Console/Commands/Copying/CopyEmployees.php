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
     * @var string sqlsrv_itam | mysql_itam
     */
    private $foreignConnection = 'sqlsrv_itam';

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
        $counter = 0;

        DB::connection($this->foreignConnection)
            ->table('employees')
            ->orderBy('id')
            ->chunk(100, function($employees) use ($counter) {

                $toInsert = [];
                foreach($employees as $employee) {

                    $this->info('Processed: ' . ++$counter);

                    $foreignDepartment = DB::connection($this->foreignConnection)->table('departments')->where('id', $employee->department_id)->first();

                    $localDepartment = Department::where('code', $foreignDepartment->code)->firstOrFail();

                    $foreignStaffType = DB::connection($this->foreignConnection)->table('staff_types')->where('id', $employee->staff_type_id)->first();

                    $localStaffType = StaffType::firstOrCreate([
                        'name' => $foreignStaffType->title,
                    ], [
                        'name' => $foreignStaffType->title,
                    ]);

                    $toInsert[] = [
                        'code' => $employee->code,
                        'department_id' => $localDepartment->id,
                        'staff_type_id' => $localStaffType->id,
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'phone' => $employee->contact_number,
                        'approver' => false,
                    ];
                }

                Employee::insert($toInsert);
            });
    }
}
