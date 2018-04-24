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

namespace App\Http\Controllers\Api;

use App\Clarimount\Service\ManufacturerService;
use App\Http\Controllers\Controller;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
	protected $service;

	public function __construct(ManufacturerService $service)
	{
		$this->service = $service;
	}

    public function index()
    {
        $this->authorize('view-manufacturers');
        return $this->service->index();
    }

    public function paginatedIndex($per_page = 10)
    {
        $this->authorize('view-manufacturers');
        return $this->service->paginatedIndex($per_page);
    }

    public function create()
	{
	    $this->authorize('create-manufacturers');
		return view('manufacturer.create');
	}

    public function store()
	{
	    $this->authorize('create-manufacturers');
		return $this->service->store();
	}

    public function edit($id)
    {
        $this->authorize('update-manufacturers');
    	$manufacturer = $this->service->edit($id);

    	return view('manufacturers.edit', compact('manufacturer'));
    }

    public function update($id)
    {
        $this->authorize('edit-manufacturers');
    	$this->service->update($id);

    	return redirect(route('manufacturers.index'));
    }

    public function destroy($id)
	{
        $this->authorize('delete-manufacturers');
		$this->service->destroy($id);

		return redirect(route('manufacturers.index'));
	}

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $results = Manufacturer::where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return $results;
    }

}
