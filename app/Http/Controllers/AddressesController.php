<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    public function index()
    {
        $deletedAddressesCounter = Address::onlyTrashed()->count();
        $activeAddressesCounter = Address::count();

        $shippingAddresses = Address::shipping()->get();
        $billingAddresses = Address::billing()->get();

        $addresses = Address::get();

        return view('addresses.index', compact(
            'addresses',
            'deletedAddressesCounter',
            'activeAddressesCounter',
            'shippingAddresses',
            'billingAddresses'
            ));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'type' => 'required|numeric',
        ]);

        $data = request()->except('_token');

        $address = Address::create($data);

        return redirect()->route('addresses.show', ['id' => $address->id]);
    }

    public function show($id)
    {
        $address = Address::findOrFail($id);
        return view('addresses.show', compact('address'));
    }

    public function edit($id)
    {
        $address = Address::findOrFail($id);
        return view('addresses.edit', compact('address'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'type' => 'required|numeric',
        ]);

        $data = request()->except(['_token', '_method']);
        Address::findOrFail($id)->update($data);

        return redirect()->route('addresses.show', ['id' => $id]);
    }

    public function destroy($id)
    {
        $address = Address::findOrFail($id);

        $address->delete();

        return redirect()->route('addresses.index');
    }
}
