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

namespace App\Clarimount\Repository;


use Spatie\Permission\Models\Role;

class RoleRepository
{
	protected $model;

	function __construct(Role $model)
	{
		$this->model = $model;
	}

	public function all()
	{
		return $this->model->all();
	}

	public function destroy($id)
	{
		return $this->model->where('id', $id)->delete();
	}

	public function create($model)
	{
		return $this->model->create($model);
	}

	public function find($id)
	{
		return $this->model->findOrFail($id);
	}

	public function findByIdWithPermissions($id)
	{
		return $this->model->where('id', $id)->with('permissions')->firstOrFail();
	}

	public function update($id, $model)
	{
		return $this->model->where('id', $id)->update($model);
	}
}
