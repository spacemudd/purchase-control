<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Http\Controllers;

use App\Clarimount\Service\DepartmentService;
use App\Clarimount\Service\EmployeeService;
use Illuminate\Http\Request;
use App\Models\StaffType;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
	protected $service;
	protected $departments_service;

	public function __construct(EmployeeService $service,
								DepartmentService $departments_service)
	{
		$this->service = $service;
		$this->departments_service = $departments_service;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function index()
	{
	    $this->authorize('view-employees');

        $activeEmployees = Employee::count();
        $inactiveEmployees = Employee::onlyTrashed()->count();

		return view('employees.index', compact('activeEmployees', 'inactiveEmployees'));
	}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function show($id)
	{
	    $this->authorize('view-employees');

        $employee = Employee::where('id', $id)->firstOrFail();
        return view('employees.show', compact('employee'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function edit($id)
	{
	    $this->authorize('update-employees');

		$employee = $this->service->show($id);
		$departments = $this->departments_service->index();
		$types = StaffType::get();

		return view('employees.edit', compact('employee', 'departments', 'types'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function update(Request $request, $id)
	{
	    $this->authorize('update-employees');

		$this->service->update($id);

		return redirect()->route('employees.show', ['id' => $id]);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function destroy($id)
	{
	    $this->authorize('delete-employees');

		$this->service->destroy($id);

		session()->flash('status', 'success');
        session()->flash('message', trans('statements.successfully-deleted'));
        
		return redirect()->route('employees.index');
	}

	public function export()
	{
        return Excel::create('export', function($excel) {

            $excel->sheet('First Sheet', function($sheet) {

                $sheet->appendRow([
                    'id',
                    'code',
                    'name',
                    'email',
                    'phone',
                    'active',
                    'created_at',
                    'updated_at',
                    'department',
                    'staff_type',
                ]);

                Employee::with(['department', 'type'])->chunk(500, function($employees) use ($sheet) {
                    foreach($employees as $employee) {
                        $modelAsArray = $employee->toArray();

                        $modelAsArray['department'] = $employee->department->name;
                        $modelAsArray['staff_type'] = $employee->type->title;

                        unset($modelAsArray['department_id']);
                        unset($modelAsArray['staff_type_id']);
                        unset($modelAsArray['type']);
                        unset($modelAsArray['link']);

                        $sheet->appendRow($modelAsArray);
                    }
                });
            });


        })->export('csv');
	}
}
