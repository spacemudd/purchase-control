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

use App\Clarimount\Repository\PermissionRepository;

class PermissionService
{
    protected $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->all();
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function store()
    {
        $permission = request()->except(['_token']);

        return $this->repository->create($permission);
    }

    public function edit($id)
    {
        return $this->repository->find($id);
    }

    public function update($id)
    {
        $permission = request()->except(['_token', '_method']);

        return $this->repository->update($id, $permission);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function allGroupByResource()
    {
        return $this->repository->allGroupByResource();
    }

}
