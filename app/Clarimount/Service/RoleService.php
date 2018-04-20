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
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Clarimount\Repository\RoleRepository;

class RoleService
{
    protected $repository;

    public function __construct(RoleRepository $repository)
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
        $role = request()->except(['_token']);

        return $this->repository->create($role);
    }

    public function edit($id)
    {
        return $this->repository->find($id);
    }

    public function update($id)
    {
        $role = request()->except(['_token', '_method']);

        return $this->repository->update($id, $role);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function showWithPermissions($id)
    {
        /**
         * Using PHP to sort the array because apparently I can't figure out
         * how to use groupBy() with a pivot table relationship.
         */
        $role_with_permissions = $this->repository->findByIdWithPermissions($id);

        $groupByResource = [];
        foreach($role_with_permissions->permissions as $permission) {
                $groupByResource[$permission->resource][] = $permission;
        }

        unset($role_with_permissions['permissions']);

        $data = ['details' => $role_with_permissions,
                'permissions_assigned' => $groupByResource];

        return $data;
    }

    /**
     * Adds a permission to the role
     * @param array
     */
    public function addPermissionToRole(Request $request)
    {
        $request = $request->except(['_token']);

        $this->validatePermissionRequest($request)->validate();

        return $this->repository->addPermissionToRole($request['role_id'], $request['resource_type']);
    }

    public function removePermissionToRole(Request $request)
    {
        $request = $request->except(['_token']);
        $this->validatePermissionRequest($request)->validate();

        return $this->repository->removePermissionToRole($request['role_id'], $request['resource_type']);
    }

    public function validatePermissionRequest(array $data)
    {
        return Validator::make($data, ['resource_type' => 'required|string|max:255',
                                        'role_id' => 'required|numeric']);
    }
}
