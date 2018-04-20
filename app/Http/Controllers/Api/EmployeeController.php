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

namespace App\Http\Controllers\Api;

use App\Clarimount\Service\EmployeeService;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\StaffType;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
	protected $service;

	public function __construct(EmployeeService $service)
	{
		$this->service = $service;
	}

    public function index()
    {
        return $this->service->index();
    }

    public function paginatedIndex($per_page = 10)
    {
        return $this->service->paginatedIndexWithDepartment($per_page);
    }

    public function assetsInCustody(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        return Employee::where('id', $request->employee_id)
            ->with(['assets_in_custody' => function($q) {
                $q->with('asset_template')
                    ->with(['issuance_item' => function($q) {
                        $q->with('issuance');
                    }]);
            }])
            ->firstOrFail()
            ->assets_in_custody;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $employees = Employee::where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->orWhere('phone', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return $employees;
    }

    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id'
        ]);

        $employee = Employee::where('id', $request->id)
            ->with('department')
            ->with('type')
            ->firstOrFail();

        return $employee;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->store();
    }

    /**
     * TODO: Remove this to be in its own controller.
     *
     * @return array
     */
    public function staffTypes()
    {
        $result['types'] = StaffType::get()->toArray();

        return $result;
    }
}
