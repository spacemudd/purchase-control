<?php

namespace App\Classes;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Brick\Money\Money;

class QuotationsByCostCenterBySupplierReportBuilder
{
    protected $vendor_id;

    public function __construct($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }

    static public function new(int $vendor_id)
    {
        return new QuotationsByCostCenterBySupplierReportBuilder($vendor_id);
    }

    public function build()
    {
        $quotations = $this->byVendor();
        $quotations = $this->loadQuotationItemsPrices($quotations);
        return $quotations;
    }

    /**
     *
     * @return
     */
    private function byVendor()
    {
        $quotations = Quotation::select('quotations.*', 'departments.description AS cost_center_code')
            ->leftJoin('vendors', 'vendors.id', '=', 'quotations.vendor_id')
            ->leftJoin('departments', 'departments.id', '=', 'quotations.cost_center_id')
            ->orderBy('quotations.created_at')
            ->where('vendor_id', $this->vendor_id)
            ->get()
            ->groupBy('cost_center_code');

        return $quotations;
    }

    public function loadQuotationItemsPrices($quotations)
    {
        $quotations->map(function($cost_center_quotations) {
            $cost_center_quotations->map(function($quotation) {
                $total_price_inc_vat = QuotationItem::where('quotation_id', $quotation->id)->sum('total_price_inc_vat');
                $quotation->total_price_inc_vat = $this->convertToMoney($total_price_inc_vat);
            });
        });

        return $quotations;
    }

    public function convertToMoney($money)
    {
        return Money::ofMinor($money, 'SAR')->getAmount();
    }
}
