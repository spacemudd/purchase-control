<?php

namespace App\Services;

use App\Classes\QuotationSave;
use App\Models\Quotation;

class QuotationsService
{
    /**
     * TODO: Should mark a material request as approved.
     *
     * Create a new supplier quotation.
     *
     * @param $id
     * @return \App\Models\Quotation
     */
    public function save($id): Quotation
    {
        $quote = Quotation::where('id', $id)->firstOrFail();
        $saved = QuotationSave::new($quote);
        return $saved->save();
    }
}
