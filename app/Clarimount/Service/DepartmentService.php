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

use App\Clarimount\Repository\DepartmentRepository;
use Illuminate\Support\Facades\Validator;

class DepartmentService
{
	protected $repository;

	public function __construct(DepartmentRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index()
	{
		return $this->repository->all();
	}

	public function paginatedIndex($per_page)
	{
		if($per_page > 100) {
			$per_page = 100;
		}

		if($per_page < 10) {
			$per_page = 10;
		}

		return $this->repository->paginatedIndex($per_page);
	}

	public function store()
	{
		$department = request()->except(['_token']);

		$this->validate($department)->validate();

		return $this->repository->create($department);
	}

	public function edit($id)
	{
		return $this->repository->find($id);
	}

	public function update($id)
	{
		$department = request()->except(['_token', '_method']);

		$this->validateOnUpdate($department, $id)->validate();

		return $this->repository->update($id, $department);
	}

	public function show($id)
	{
		return $this->repository->find($id);
	}

	public function validate(array $data)
	{
		return Validator::make($data, [
		    'code' => 'required|string|max:255|unique:departments',
            'description' => 'required|string|max:255',
            'head_department' => 'nullable|string|max:255',
        ]);
	}

	public function validateOnUpdate(array $data, $id)
	{
        return Validator::make($data, [
            'code' => 'required|string|max:255|unique:departments,code,' . $id,
            'description' => 'required|string|max:255',
            'head_department' => 'nullable|string|max:255',
        ]);
	}

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

}
