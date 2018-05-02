<?php

namespace App\Http\Controllers;

use App\Clarimount\Service\ApproversService;
use App\Models\Employee;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApproversController extends Controller
{
    protected $service;

    public function __construct(ApproversService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $this->authorize('view-approvers');

        $approvers = $this->service->all();

        return view('approvers.index', compact('approvers'));
    }

    public function create()
    {
        $this->authorize('create-approvers');

        return view('approvers.create');
    }

    public function show($id)
    {
        $this->authorize('view-approvers');

        $approver = Employee::where('approver', true)->where('id', $id)->firstOrFail();

        return view('approvers.show', compact('approver'));
    }

    public function edit($id)
    {
        $this->authorize('edit-approvers');

        $approver = Employee::where('approver', true)
            ->where('id', $id)
            ->firstOrFail();

        return view('approvers.edit', compact('approver'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'financial_auth' => 'required|numeric|min:0',
            'designation' => 'nullable|string|max:255',
        ]);

        $employee = Employee::where('id', $id)->firstOrFail();

        if(!$employee->approver) {
            session()->flash('errors', 'Employee is not an approver');
            return redirect()->back();
        }

        $employee = DB::transaction(function() use ($request, $id) {

            $employee = Employee::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $employee->financial_auth = Money::of($request->financial_auth, 'SAR')->getMinorAmount()->toInt();
            $employee->designation = $request->designation;
            $employee->save();

            return $employee;
        }, 2);

        return redirect()->route('approvers.show', ['id' => $id]);
    }
}
