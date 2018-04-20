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

use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\VendorRepository;
use Spatie\Permission\Exceptions\UnauthorizedException;

class VendorService
{
	protected $repository;

	public function __construct(VendorRepository $repository)
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
		$vendor = request()->except(['_token']);

		$this->validate($vendor)->validate();

		return $this->repository->create($vendor);
	}

	public function edit($id)
	{
		return $this->repository->find($id);
	}

	public function update($id)
	{
        $vendor = request()->except(['_token', '_method']);

		$this->validate($vendor, $id)->validate();

	    $this->repository->update($id, $vendor);

		return $vendor;
	}

	public function show($id)
	{
		return $this->repository->find($id);
	}

	public function validate(array $data)
	{
		return Validator::make($data, [
		    'name' => 'required|string|max:255',
            'established_at' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'telephone_number' => 'nullable|string|max:255',
            'fax_number' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'website' => 'nullable|string|max:255',
        ]);
	}
}
