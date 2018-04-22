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

use App\Clarimount\Repository\VendorRepresentativeRepository;
use Illuminate\Support\Facades\Validator;

class VendorRepresentativeService
{
    protected $repository;

    public function __construct(VendorRepresentativeRepository $repository)
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
        $rep = request()->except(['_token']);

        $this->validate($rep)->validate();

        return $this->repository->create($rep);
    }

    public function edit($id)
    {
        return $this->repository->find($id);
    }

    public function update($id)
    {
        $rep = request()->except(['_token', '_method']);

        $this->validate($rep, $id)->validate();

        $this->repository->update($id, $rep);

        return $rep;
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'number' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);
    }
}
