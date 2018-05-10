<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressesController extends Controller
{
    public function searchBillingAddresses()
    {
        $search = request()->input('q');

        $results = Address::billing()
            ->where('location', 'LIKE', '%' . $search . '%')
            ->paginate(100);

        return $results;
    }

    public function searchShippingAddresses()
    {
        $search = request()->input('q');

        $results = Address::shipping()
            ->where('location', 'LIKE', '%' . $search . '%')
            ->paginate(100);

        return $results;
    }
}
