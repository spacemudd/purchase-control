<?php

namespace App\Http\Controllers;

use App\Clarimount\Service\VendorBankService;
use App\Models\CompanyJournal;
use App\Models\SalesTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesTaxesController extends Controller
{
    public function index()
    {
        $this->authorize('view-sales-taxes');

        $salesTaxes = SalesTax::get();

        return view('sales-taxes.index', compact('salesTaxes'));
    }

    public function create()
    {
        $this->authorize('create-sales-taxes');

        $currencies = app()->make(VendorBankService::class)
            ->currencies();

        return view('sales-taxes.create', compact('currencies'));
    }

    public function show($id)
    {
        $this->authorize('view-sales-taxes');

        $salesTax = SalesTax::findOrFail($id);

        return view('sales-taxes.show', compact('salesTax'));
    }

    public function destroy($id)
    {
        // todo: when the sales tax's company journal has transactions under it, this shouldn't be deleted.
        SalesTax::find($id)->delete();

        return redirect()->route('sales-taxes.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tax_name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
            'current_tax_rate' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'currency' => 'required|string|max:3',
        ]);

        $data = $request->except('_token');

        $data['show_tax_number_on_invoices'] = false;
        $data['is_recoverable'] = false;
        $data['is_compound'] = false;

        // To move this somewhere else.
        if(CompanyJournal::where('name', $data['tax_name'])->exists()) {
            session()->flash('status', 'is-warning');
            session()->flash('messages', ['The tax name has already been taken.']);
            return redirect()->back();
        }


        $salesTax = DB::transaction(function() use ($data) {

            $companyJournal = \App\Models\CompanyJournal::create([
                'name' => $data['tax_name'],
                'system_account' => true,
            ]);

            $companyJournal->initJournal($data['currency']);

            $salesTax = SalesTax::create([
                'tax_name' => $data['tax_name'],
                'abbreviation' => $data['abbreviation'],
                'current_tax_rate' => $data['current_tax_rate'],
                'description' => $data['description'],
                'tax_number' => '',
                'show_tax_number_on_invoices' => false,
                'is_recoverable' => false,
                'is_compound' => false,
                'tax_account_id' => $companyJournal->id,
            ]);

            return $salesTax;
        });

        return redirect()->route('sales-taxes.show', ['id' => $salesTax->id]);
    }
}
