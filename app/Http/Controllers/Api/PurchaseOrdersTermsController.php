<?php

namespace App\Http\Controllers\Api;

use App\Model\PurchaseTerm;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrdersTermsController extends Controller
{
    public function attach(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'term_id' => 'required|exists:purchase_terms,id',
        ]);

        $po = PurchaseOrder::find($request->purchase_order_id);
        $term = PurchaseTerm::find($request->term_id);

        $po->terms()->attach($term);

        $savedTerms = [];
        foreach($po->terms()->get() as $term) {
            $savedTerms[$term->type->name][] = $term;
        }

        $po->terms_json = $savedTerms;
        $po->save();

        return $po;
    }

    public function detach(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'term_id' => 'required|exists:purchase_terms,id',
        ]);

        $po = PurchaseOrder::find($request->purchase_order_id);
        $term = PurchaseTerm::find($request->term_id);

        $po->terms()->detach($term);

        $savedTerms = [];
        foreach($po->terms()->get() as $term) {
            $savedTerms[$term->type->name][] = $term;
        }

        $po->terms_json = $savedTerms;
        $po->save();

        return $po;
    }
}
