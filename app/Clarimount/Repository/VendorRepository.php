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

use App\Models\UserActivity;
use App\Models\Vendor;

class VendorRepository
{
	protected $model;

	function __construct(Vendor $model)
	{
		$this->model = $model;
	}

	public function all()
	{
		return $this->model->with('manufacturers')->all();
	}

	public function paginatedIndex($per_page)
	{
		return $this->model->latest()->paginate($per_page);
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
		return $this->model->with('manufacturers')->findOrFail($id);
	}

	public function update($id, $model)
	{
		return $this->model->where('id', $id)->update($model);
	}

}
