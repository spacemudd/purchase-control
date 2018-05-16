<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionSimpleItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseRequisitionSimpleItemsController extends Controller
{
    /**
     * Get the simple items of a purchase requisitions.
     *
     * @param $purchase_requisition_id
     * @return mixed
     */
    public function index($purchase_requisition_id)
    {
        $simpleItems = PurchaseRequisition::where('id', $purchase_requisition_id)
            ->firstOrFail()
            ->simple_items;

        return $simpleItems;
    }

    /**
     * Stores a simple item ID to the PR.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_requisition_id' => 'required|exists:purchase_requisitions,id',
            'description' => 'required|string|max:255',
            'qty' => 'required',
        ]);

        // todo: make sure the PR is in valid.

        $data = $request->except(['_token']);
        $item = PurchaseRequisitionSimpleItem::create($data);

        return $item;
    }

    public function delete($id)
    {
        // todo: validation + make sure the request is NOT saved.

        PurchaseRequisitionSimpleItem::where('id', $id)->delete();
    }
}
