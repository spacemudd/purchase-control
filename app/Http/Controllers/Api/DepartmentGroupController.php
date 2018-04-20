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

use App\Models\DepartmentGroup;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentGroupController extends Controller
{
    public function index()
    {
        return DepartmentGroup::with('departments')->get();
    }

    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:department_groups'
        ]);

        return DepartmentGroup::where('id', $request->id)->with('departments')->firstOrFail();
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|max:100|unique:department_groups',
            'description' => 'required|max:255',
        ]);

        $department = new DepartmentGroup();
        $department->code = $request->code;
        $department->description = $request->description;
        $department->active = true;
        $department->save();

        return $department;
    }

    public function attach(Request $request)
    {
        $request->validate([
            'department_group_id' => 'required|exists:department_groups,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $departmentGroup = DepartmentGroup::where('id', $request->department_group_id)->firstOrFail();
        $department = Department::where('id', $request->department_id)->firstOrFail();

        $departmentIdsToSync[] = $department->id;

        $departmentsAlreadyAttached = $departmentGroup->departments()->get();
        foreach($departmentsAlreadyAttached as $department) {
            $departmentIdsToSync[] = $department->id;
        }

        return $departmentGroup->departments()->sync($departmentIdsToSync);
    }

    public function detach(Request $request)
    {
        $request->validate([
            'department_group_id' => 'required|exists:department_groups,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $departmentGroup = DepartmentGroup::where('id', $request->department_group_id)->firstOrFail();
        $department = Department::where('id', $request->department_id)->firstOrFail();

        return $departmentGroup->departments()->detach($department);
    }
}
