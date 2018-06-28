<?php

namespace App\Http\Controllers;

use App\Model\PurchaseTermsType;
use Illuminate\Http\Request;

class PurchasingTermsController extends Controller
{
    public function index()
    {
        $purchasingTypes = PurchaseTermsType::with('terms')->get();

        return view('purchasing-terms.index', compact('purchasingTypes'));
    }
}
