<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseRequisitionNotes extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:purchase_requisitions',
        ]);

        $notes = PurchaseRequisition::findOrFail($request->id)->notes()->with('user')->get();

        return $notes;
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchase_requisitions',
            'body' => 'required|string|max:255',
        ]);

        $note = new Note([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
        ]);

        $requisition = PurchaseRequisition::findOrFail($request->id)
            ->notes()
            ->save($note)
            ->load('user');

        return $requisition;
    }
}
