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

use App\Models\ReferenceType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferenceTypeController extends Controller
{
    public function index()
    {
        return ReferenceType::get()->toArray();
    }

    public function store(Request $request)
    {
        $newReferenceType = $request->validate([
            'name' => 'required|unique:reference_types|max:100',
        ]);

        $new = new ReferenceType();
        $new->name = $request->name;
        $new->slug = str_slug($request->name);
        $new->description = $request->name; // TODO: Refactor.
        $new->save();

        return $new;
    }

    public function update(Request $request)
    {
        $type = $request->validate([
            'id' => 'required|exists:reference_types,id',
            'name' => 'required|string|max:100',
            'active' => 'required|boolean',
        ]);

        $updated = ReferenceType::where('id', $request->id)->update($type);

        if($updated) {
            $message = 'Success';
        } else {
            $message = 'Failed';
        }

        return response()->json(['message' => $message]);
    }
}
