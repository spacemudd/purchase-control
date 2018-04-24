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

use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Clarimount\Service\ItemService;
use App\Models\ItemTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
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

        $items = Item::where('code', 'LIKE',' %' . $request->q . '%')
            ->orWhere('name', 'LIKE', $request->q . '%')
            ->orWhere('description', 'LIKE', $request->q . '%')
            ->paginate(10);

        return $items;
    }
}
