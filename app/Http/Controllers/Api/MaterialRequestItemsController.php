<?php

namespace App\Http\Controllers\Api;

use App\Models\MaterialRequestItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialRequestItemsController extends Controller
{
    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $results = MaterialRequestItem::where('description', 'LIKE', '%' . $search . '%')
            ->with('material_request')
            ->whereHas('material_request', function($q) {
                $q->pending();
            })
            ->paginate(10);

        return $results;
    }
}
