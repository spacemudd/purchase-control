<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Http\Request;

class QuotationItemsController extends Controller
{
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Brick\Money\Exception\MoneyMismatchException
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function store(Request $request)
    {
        $request->validate([
           'quotation_id' => 'required|exists:quotations,id',
           'description' => 'required|string|max:255',
           'qty' => 'required|numeric',
           'unit_price' => 'required|numeric',
        ]);

        $request = $request->except('_token');

        $request['vendor_id'] = Quotation::where('id', $request['quotation_id'])->first()->vendor_id;

        $totalPriceExVat = Money::of($request['unit_price'], 'SAR')
            ->multipliedBy($request['qty'], RoundingMode::HALF_UP);

        $request['total_price_ex_vat'] = $totalPriceExVat->getMinorAmount()->toInt();

        $vat = Money::ofMinor($totalPriceExVat->getMinorAmount()->toInt(), 'SAR')
            ->multipliedBy(0.05, RoundingMode::HALF_UP);

        $request['vat'] = $vat->getMinorAmount()
            ->toInt();

        $totalPriceIncVat = Money::ofMinor($totalPriceExVat->getMinorAmount()->toInt(), 'SAR')
            ->plus($vat);

        $request['total_price_inc_vat'] = $totalPriceIncVat
            ->getMinorAmount()
            ->toInt();

        return QuotationItem::create($request);
    }

    /**
     *
     * @param $quotationId
     * @param $id
     * @return array
     */
    public function delete($quotationId, $id)
    {
        QuotationItem::where('id', $id);
        return [
            'message' => 'Success',
        ];
    }
}
