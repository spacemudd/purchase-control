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

        $poTerms = $po->terms_json;

        foreach ($poTerms as $type => &$jsonTerms) {
            if ($type == 'Others') break;

            foreach ($jsonTerms as $jsonTerm) {
                if ($jsonTerm->value->id === $term->id) {
                    $jsonTerm->enabled = true;
                }
            }
        }

        $po->terms_json = $poTerms;
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

        $poTerms = $po->terms_json;

        foreach($poTerms as $type => &$jsonTerms) {
            if($type == 'Others') break;
                foreach ($jsonTerms as $jsonTerm) {
                    if ($jsonTerm->value->id === $term->id) {
                        $jsonTerm->enabled = false;
                    }
                }
        }

        $po->terms_json = $poTerms;
        $po->save();

        return $po;
    }
}
