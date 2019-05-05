<?php

namespace App\Http\Controllers\Api;

use App\Classes\QuotationsByCostCenterBySupplierReportBuilder;
use App\Models\Vendor;
use App\Http\Controllers\Controller;
use Brick\Money\Money;

class QSuppliersController extends Controller
{
    public function index()
    {
        return Vendor::get();
    }

    /**
     *
     * @param $id Vendor ID?
     * @return mixed
     */
    public function showWithQuotations($id)
    {
        return QuotationsByCostCenterBySupplierReportBuilder::new($id)->build();
    }

    /**
     * Show the balance of the supplier.
     *
     * @param $id
     * @return array
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function balance($id)
    {
        $vendor = Vendor::findOrFail($id);

        return [
            'id' => $id,
            'code' => $vendor->code,
            'name' => $vendor->name,
            'balance' => Money::ofMinor($vendor->journal->getBalanceInDollars(), 'SAR')->getAmount(),
        ];
    }
}
