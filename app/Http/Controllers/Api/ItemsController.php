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

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Clarimount\Service\ItemService;
use App\Models\Item;

class ItemsController extends Controller
{
    protected $service;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    public function store()
    {
        $request = request()->except('_token');

        return $this->service->store($request);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255|min:0',
        ]);

        return Item::where('name', 'LIKE','%' . $request->q . '%')->get();
    }
}
