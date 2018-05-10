<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderNotesController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:purchase_orders',
        ]);

        $notes = PurchaseOrder::findOrFail($request->id)->notes()->with('user')->get();

        return $notes;
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchase_orders',
            'body' => 'required|string|max:255',
        ]);

        $note = new Note([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
        ]);

        $requisition = PurchaseOrder::findOrFail($request->id)
            ->notes()
            ->save($note)
            ->load('user');

        return $requisition;
    }
}
