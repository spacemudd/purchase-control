<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesTax;
use App\Http\Controllers\Controller;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Illuminate\Http\Request;

class SalesTaxesController extends Controller
{
    public function index()
    {
        return SalesTax::get();
    }

    /**
     * Calculates the total amount tax for each tax that's
     * attached to the item.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'subtotal' => 'numeric',
            'taxes' => 'required',
        ]);

        $totalTaxes = [];

        foreach($request->taxes as $tax) {
            $salesTax = SalesTax::where('id', $tax['id'])->firstOrfail();

            $subtotal = Money::of($request->subtotal, Currency::of(trim($salesTax->company_journal->journal->currency)));

            $totalTaxes[] = [
                'tax_id' => $salesTax->id,
                'abbreviation' => $salesTax->abbreviation,
                'amount' => $subtotal->multipliedBy($salesTax->current_tax_rate/100, RoundingMode::HALF_UP)->getAmount(),
            ];
        }

        return $totalTaxes;
    }
}
