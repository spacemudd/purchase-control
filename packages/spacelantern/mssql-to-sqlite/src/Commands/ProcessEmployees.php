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
use App\Models\Department;
use App\Models\Employee;
use App\Models\StaffType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ProcessEmployees extends Command
{
    protected $signature = 'spacelantern:process-employees';

    protected $description = 'Process all employees';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->process();

        $this->info('');

        $this->info('Employees: Completed');
    }

    public function getAllItems() {
        $step = 100;

        for ($offset = 0 ;; $offset += $step) {
            $q = DB::raw('SELECT * FROM dbo.Employee ORDER BY EmployeeId OFFSET '. $offset .' ROWS FETCH NEXT '. $step . ' ROWS ONLY');

            if (! $items = DB::connection('sqlsrv_old::read')->select($q)) {
                break;
            }

            yield $items;
        }
    }

    public function process()
    {
        DB::disableQueryLog();

        Employee::unguard();

        $imports = 1;

        foreach($this->getAllItems() as $foreignRecords) {

            $recordsToInsert = [];

            foreach($foreignRecords as $foreignRecord) {

                $this->info('Processing record & relation (dept. & staff type) for employee counter: ' . $imports++);

                $department = $this->findDepartment($foreignRecord->DepartmentId);
                $staffType = $this->findStaffType($foreignRecord->StaffTypeId);

                $recordsToInsert[] = [
                    'code' => $foreignRecord->Code,
                    'name' => $foreignRecord->EmployeeName,
                    'email' => $foreignRecord->Email,
                    'contact_number' => $foreignRecord->ContactNumber,
                    'active' => $foreignRecord->ActiveStatus,
                    'created_at' => Carbon::parse($foreignRecord->CreationDate),
                    'updated_at' => Carbon::parse($foreignRecord->ModificationDate),

                    'department_id' => $department->id,
                    'staff_type_id' => $staffType->id,
                ];
            }

            Employee::insert($recordsToInsert);
        }
    }

    public function findDepartment(int $foreignId): Department
    {
        $foreignRecord = DB::connection('sqlsrv_old')->select('SELECT * from dbo.Department WHERE DepartmentId = ?', [
            $foreignId,
        ]);

        $foreignCode = $foreignRecord[0]->Code;

        $department = Department::where('code', $foreignCode)->firstOrFail();

        return $department;
    }

    public function findStaffType(int $foreignId): StaffType
    {
        $foreignRecord = DB::connection('sqlsrv_old')->select('SELECT * from dbo.StaffType WHERE StaffTypeId = ?', [
            $foreignId,
        ]);

        $foreignCode = $foreignRecord[0]->Code;

        $staffType = StaffType::where('title', $foreignCode)->firstOrFail();

        return $staffType;
    }
}
