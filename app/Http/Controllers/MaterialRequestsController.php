<?php

namespace App\Http\Controllers;

use App\Classes\MaterialRequestExcel;
use App\Classes\MaterialRequestExcelAll;
use App\Models\Department;
use App\Models\Location;
use App\Models\MaterialRequest;
use App\Services\MaterialRequestService;
use Illuminate\Http\Request;

class MaterialRequestsController extends Controller
{
    protected $service;

    public function __construct(MaterialRequestService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mRequests = MaterialRequest::latest()->get();
        return view('material-requests.index', compact('mRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::get();
        $departments = Department::get();
        return view('material-requests.create', compact('locations', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'location_id' => 'required|numeric|exists:locations,id',
            'cost_center_id' => 'required|numeric|exists:departments,id',
        ]);

        if ($this->service->checkNumberExists($request['date'], (int) $request['location_id'])) {
            return 'Material request already exists!';
        }

        $materialRequest = $this->service->save($request->except('_token'));

        return redirect()->route('material-requests.show', ['id' => $materialRequest->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mRequest = MaterialRequest::findOrFail($id);

        return view('material-requests.show', compact('mRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function approve($id)
    {
        $mRequest = $this->service->approve($id);
        return redirect()->route('material-requests.show', ['id' => $mRequest->id]);
    }

    public function excel($id)
    {
        return MaterialRequestExcel::find($id)->download();
    }

    public function allExcel()
    {
        return MaterialRequestExcelAll::new()->download();
    }
}
