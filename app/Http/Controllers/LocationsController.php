<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name'
        ]);

        $locations = Location::create($request->except('_token'));

        return redirect()->route('dashboard.index');
    }
}
