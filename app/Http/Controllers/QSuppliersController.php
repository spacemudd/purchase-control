<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * QuotationsSuppliers.
 *
 * @package App\Http\Controllers
 */
class QSuppliersController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('q-suppliers.index');
    }
}
