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

        $manufacturer['created_by_id'] = auth()->user()->id;

        $createdManufacturer = $this->repository->create($manufacturer);

        $createdManufacturer->created_by_id = auth()->user()->id;
        $createdManufacturer->save();

        return $createdManufacturer;
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
            'name' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'support_url' => 'nullable|string|max:255',
            'support_phone' => 'nullable|string|max:255',
            'support_email' => 'nullable|string|max:255',
        ]);
    }

}
