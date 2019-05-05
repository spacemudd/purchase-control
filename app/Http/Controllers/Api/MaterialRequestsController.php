<?php

namespace App\Http\Controllers\Api;

use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialRequestsController extends Controller
{
    public function indexWithApprovedItems()
    {
        return MaterialRequest::approved()->with('items')->get();
    }
}
