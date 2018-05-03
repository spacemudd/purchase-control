<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Brick\Money\Money;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApproversController extends Controller
{
    public function searchWithoutApproverInformation()
    {
        $search = request()->input('q');

        $employees = Employee::where('approver', '!=', true)
            ->where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->paginate(30);

        return $employees;
    }

    public function search()
    {
        $search = request()->input('q');

        $employees = Employee::where('approver', true)
            ->where('code', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->paginate(30);

        return $employees;
    }

    /**
     * Stores a new approver information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'financial_auth' => 'required|numeric|min:0',
            'designation' => 'nullable|string|max:255',
        ]);

        $employee = Employee::where('id', $request->employee_id)->firstOrFail();

        if($employee->approver) {
            return response()->json(['errors' =>
                ['Employee is already an approver'],
            ], 402);
        }

        $employee = DB::transaction(function() use ($request) {

            $employee = Employee::where('id', $request->employee_id)->lockForUpdate()->firstOrFail();

            $employee->approver = true;
            $employee->financial_auth = Money::of($request->financial_auth, 'SAR')->getMinorAmount()->toInt();
            $employee->designation = $request->designation;
            $employee->financial_auth_currency = 'SAR';
            $employee->save();

            return $employee;
        }, 2);

        return $employee;
    }

    public function delete($id)
    {
        DB::transaction(function() use ($id) {

            $employee = Employee::where('id', $id)->lockForUpdate()->firstOrFail();
            if(!$employee->approver) abort(404);

            $employee->approver = false;
            $employee->financial_auth = null;
            $employee->designation = null;
            $employee->financial_auth_currency = null;
            $employee->save();

            return $employee;
        }, 2);

        return response()->json([
            'redirect' => route('approvers.index'),
        ]);
    }
}
