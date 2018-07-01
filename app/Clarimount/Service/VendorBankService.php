<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Clarimount\Service;

use App\Clarimount\Repository\VendorBankRepository;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\VendorRepository;

class VendorBankService
{
	protected $repo;

	public function __construct(VendorBankRepository $repo)
	{
		$this->repo = $repo;
	}

	public function index()
	{
		return $this->repo->all();
	}

	public function paginatedIndex($per_page = 10)
	{
		if($per_page > 100) {
			$per_page = 100;
		}

		if($per_page < 10) {
			$per_page = 10;
		}

		return $this->repo->paginatedIndex($per_page);
	}

	public function destroy($id)
	{
		return $this->repo->destroy($id);
	}

	public function store()
	{
		$vendorBank = request()->except(['_token']);

		$this->validate($vendorBank)->validate();

		return $this->repo->create($vendorBank);
	}

	public function edit($id)
	{
		return $this->repo->find($id);
	}

	public function update($id)
	{
        $vendorBank = request()->except(['_token', '_method']);

		$this->validate($vendorBank, $id)->validate();

	    return $this->repo->update($id, $vendorBank);
	}

	public function show($id)
	{
		return $this->repo->find($id);
	}

	public function validate(array $data)
	{
		return Validator::make($data, [
		    'vendor_id' => 'required|exists:vendors,id',
		    'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'beneficiary_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban_code' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'sort_code' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:3',
        ]);
	}

    /**
     * Returns a list of currencies.
     *
     * @return array
     */
	public function currencies()
	{
	    $currencies = [
            'SAR' => 'Saudi Riyal',
            'USD' => 'US Dollar',
            'GBP' => 'British Pound',
            'EUR' => 'Euro',
            'CHF' => 'Swiss Franc',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar',
            ];

	    return $currencies;
	}
}
