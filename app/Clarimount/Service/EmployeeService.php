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

namespace App\Clarimount\Service;

use App\Clarimount\Repository\EmployeeRepository;
use Illuminate\Support\Facades\Validator;

class EmployeeService
{
    protected $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->all();
    }

    public function paginatedIndex($per_page = 10)
    {
        if($per_page > 100) {
            $per_page = 100;
        }

        if($per_page < 10) {
            $per_page = 10;
        }

        return $this->repository->paginatedIndex($per_page);
    }

    function paginatedIndexWithDepartment($per_page = 10) {
        if($per_page > 100) {
            $per_page = 100;
        }

        if($per_page < 10) {
            $per_page = 10;
        }

        return $this->repository->paginatedIndexWithDepartment($per_page);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function store()
    {
        $employee = request()->except(['_token']);

        $this->validate($employee)->validate();

        return $this->repository->create($employee);
    }

    public function edit($id)
    {
        return $this->repository->find($id);
    }

    public function update($id)
    {
        $employee = request()->except(['_token', '_method']);

        $this->validateOnUpdate($employee, $id)->validate();

        return $this->repository->update($id, $employee);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'code' => 'required|string|unique:employees,code|max:20',
            'department_id' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'staff_type_id' => 'nullable|exists:staff_types,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
        ]);
    }

    public function validateOnUpdate(array $data, $id)
    {
        return Validator::make($data, [
            'code' => 'required|string|max:30|unique:employees,code,'.$id,
            'department_id' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'staff_type_id' => 'nullable|exists:staff_types,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
        ]);
    }

}
