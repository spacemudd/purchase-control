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

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Clarimount\Service\RequestsService;
use App\Models\RequestDocument;

class RequestsController extends Controller
{
    protected $service;

    public function __construct(RequestsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $draftCounter = RequestDocument::where('status', RequestDocument::DRAFT)->count();
        $savedCounter = RequestDocument::where('status', RequestDocument::SAVED)->count();
        $voidCounter = RequestDocument::where('status', RequestDocument::VOID)->count();

        return view('requests.index', compact('draftCounter', 'savedCounter', 'voidCounter'));
    }

    /**
     *
     * @param string $statusSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paginatedByStatus(string $statusSlug)
    {
        $records = RequestDocument::query();

        switch ($statusSlug) {
            case 'draft':
                $records->draft();
                break;

            case 'saved':
                $records->saved();
                break;

            case 'void':
                $records->void();
                break;

            default:
                abort(404);
        }

        $data = $records->latest()->paginate(50);
        return view('requests.paginated-by-status', compact('data', 'statusSlug'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $employees = Employee::get();
        return view('requests.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return void
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $request = $this->service->find($id);

        return view('requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return void
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function destroy()
    {

    }
}
