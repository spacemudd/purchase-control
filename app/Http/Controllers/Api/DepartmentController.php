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

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

use App\Clarimount\Service\DepartmentService;

class DepartmentController extends Controller
{
	protected $service;

	public function __construct(DepartmentService $service)
	{
		$this->service = $service;
	}

    public function index()
    {
        $this->authorize('view-departments');

        return $this->service->index();
    }

    public function show(Request $request)
    {
        $this->authorize('view-departments');

        $id = $request->get('id');

        return $this->service->show($id);
    }

    public function paginatedIndex($per_page = 10)
    {
        $this->authorize('view-departments');

        return $this->service->paginatedIndex($per_page);
    }

    public function create()
	{
        $this->authorize('create-departments');

		return view('department.create');
	}

    public function store()
	{
        $this->authorize('create-departments');

        $data = request();

		$department = $this->service->store($data);

		if(request()->expectsJson()) {
		    return $department;
        }

		return redirect(route('departments.index'));
	}

    public function edit($id)
    {
        $this->authorize('update-departments');

    	$department = $this->service->edit($id);

    	return view('departments.edit', compact('department'));
    }

    public function update($id)
    {
        $this->authorize('update-departments');

    	$this->service->update($id);

    	return redirect(route('departments.index'));
    }

    public function destroy($id)
	{
        $this->authorize('delete-departments');

		$this->service->destroy($id);

		return redirect(route('departments.index'));
	}

    /**
     * Retrieves all the assets under the department.
     *
     * @param $id Department ID
     * @return array
     */
	public function assets($id)
	{
        $department = Department::where('id', $id)
            ->with(['employees' => function($q) {
                $q->with(['assets_in_custody' => function($q) {
                    $q->with('employee')
                       ->with('asset_template')
                       ->with(['issuance_item' => function($q) {
                            $q->with('issuance');
                        }]);
                }]);
            }])
            ->firstOrFail();

        $assets = [];
        foreach($department->employees as $employee) {
            foreach($employee->assets_in_custody as $asset) {
                $assets[] = $asset;
            }
        }

        return $assets;
	}

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $results = Department::where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return $results;
    }
}
