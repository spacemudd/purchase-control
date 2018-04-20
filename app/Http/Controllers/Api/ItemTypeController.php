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

use App\Models\ItemType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemTypeController extends Controller
{
    public function index()
    {
        return ItemType::get();
    }
}
