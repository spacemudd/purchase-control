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

use App\Clarimount\Repository\ItemRepository;
use App\Models\WorkOrder;
use App\Models\WorkRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ItemService
{
	protected $repository;

	public function __construct(ItemRepository $repository)
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

	public function destroy($id)
	{
		return $this->repository->destroy($id);
	}

	public function store()
	{
		$item = request()->except(['_token']);

		$this->validate($item)->validate();

		return $this->repository->create($item);
	}

	public function edit($id)
	{
		return $this->repository->find($id);
	}

	public function update($id)
	{
		$item = request()->except(['_token', '_method']);

		$this->validateUpdate($item)->validate();

		if( ! $item['warranty_expiry'] == null) {
			$item['warranty_expiry'] = Carbon::createFromFormat('d/m/Y', $item['warranty_expiry']);
		}

		return $this->repository->update($id, $item);
	}

	public function show($id)
	{
		return $this->repository->find($id);
	}

	public function validate(array $data)
	{
		return Validator::make($data, [
		    'item_id' => 'nullable',
            'name' => 'required',
            'qty' => 'required|numeric',
            'unit_price' => 'required|numeric',
        ]);
	}
}
