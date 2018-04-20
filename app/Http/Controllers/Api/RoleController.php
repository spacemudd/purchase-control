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

namespace App\Http\Controllers\Api;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Attaches a permission to role.
     *
     * @param \Illuminate\Http\Request $request must have role_id & permission_name.
     * @return mixed
     */
    public function attachPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_name' => 'required|exists:permissions,name',
        ]);

        $role = DB::transaction(function() use ($request) {
            $role = Role::where('id', $request->get('role_id'))->lockForUpdate()->firstOrFail();
            $permission = Permission::where('name', $request->get('permission_name'))->lockForUpdate()->firstOrFail();

            return $role->givePermissionTo($permission);
        }, 5);

        return $role;
    }

    /**
     * Removes a permission from a role.
     *
     * @param \Illuminate\Http\Request $request required role_id & permission_name
     * @return mixed
     */
    public function detachPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_name' => 'required|exists:permissions,name',
        ]);

        $role = DB::transaction(function() use ($request) {
            $role = Role::where('id', $request->get('role_id'))->lockForUpdate()->firstOrFail();
            $permission = Permission::where('name', $request->get('permission_name'))->lockForUpdate()->firstOrFail();

            return $role->revokePermissionTo($permission);
        }, 5);

        return $role;
    }
}
