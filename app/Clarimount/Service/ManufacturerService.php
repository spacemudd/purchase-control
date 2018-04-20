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

use App\Clarimount\Repository\ManufacturerRepository;

use Illuminate\Support\Facades\Validator;

class ManufacturerService
{
    protected $repository;

    public function __construct(ManufacturerRepository $repository)
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

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function store()
    {
        $manufacturer = request()->except(['_token']);

        $this->validate($manufacturer)->validate();

        return $this->repository->create($manufacturer);
    }

    public function edit($id)
    {
        return $this->repository->find($id);
    }

    public function update($id)
    {
        $manufacturer = request()->except(['_token', '_method']);

        $this->validate($manufacturer)->validate();

        return $this->repository->update($id, $manufacturer);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'code' => 'required|string|max:20|unique:manufacturers,code',
            'description' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);
    }

}
