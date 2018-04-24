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

namespace App\Http\Controllers\Back;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('back.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $roles = Role::get();
        return view('back.users.show', compact('user', 'roles'));
    }

    public function attachRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = DB::transaction(function() use ($request) {
            $role = Role::where('id', $request->get('role_id'))->lockForUpdate()->firstOrFail();
            $user = User::where('id', $request->get('user_id'))->lockForUpdate()->firstOrFail();

            return $user->assignRole($role);
        }, 5);

        return $user;
    }

    public function detachRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = DB::transaction(function() use ($request) {
            $role = Role::where('id', $request->get('role_id'))->lockForUpdate()->firstOrFail();
            $user = User::where('id', $request->get('user_id'))->lockForUpdate()->firstOrFail();

            return $user->removeRole($role);
        }, 5);

        return $user;
    }

    public function invite()
    {
        return view('back.users.invite');
    }
}
