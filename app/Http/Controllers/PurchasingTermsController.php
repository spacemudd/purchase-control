<?php

namespace App\Http\Controllers;

use App\Model\PurchaseTerm;
use App\Model\PurchaseTermsType;
use Illuminate\Http\Request;

class PurchasingTermsController extends Controller
{
    public function index()
    {
        $purchasingTypes = PurchaseTermsType::with('terms')->get();

        return view('purchasing-terms.index', compact('purchasingTypes'));
    }

    /**
     * Enables a term by default.
     *
     * @param \Illuminate\Http\Request $request
     * @return PurchaseTerm
     */
    public function enable(Request $request)
    {
        $request->validate([
            'term_id' => 'required|exists:purchase_terms,id',
        ]);

        $term = PurchaseTerm::find($request->term_id);
        $term->enabled = true;
        $term->save();

        return $term;
    }

    /**
     * disable a term by default.
     *
     * @param \Illuminate\Http\Request $request
     * @return PurchaseTerm
     */
    public function disable(Request $request)
    {
        $request->validate([
            'term_id' => 'required|exists:purchase_terms,id',
        ]);

        $term = PurchaseTerm::find($request->term_id);
        $term->enabled = false;
        $term->save();

        return $term;
    }
}
