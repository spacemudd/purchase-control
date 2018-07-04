<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesTaxesController extends Controller
{
    public function create()
    {
        $this->authorize('create-sales-taxes');

        return view('sales-taxes.create');
    }
}
