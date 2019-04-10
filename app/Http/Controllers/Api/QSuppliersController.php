<?php

namespace App\Http\Controllers\Api;

use App\Classes\QuotationsByCostCenterBySupplierReportBuilder;
use App\Models\Vendor;
use App\Http\Controllers\Controller;

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
}
