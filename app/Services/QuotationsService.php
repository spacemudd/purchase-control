<?php

namespace App\Services;

use App\Classes\QuotationSave;
use App\Models\Quotation;

class QuotationsService
{
    /**
     * Marks a material request as approved.
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
