<?php

namespace App\Classes;

use App\Models\Quotation;

class QuotationSave
{
    /**
     * @var \App\Models\MaterialRequest 
     */
    private $quotation;

    static public function new(Quotation $quotation): QuotationSave
    {
        return new QuotationSave($quotation);
    }

    /**
     * MaterialRequestApproval constructor.
     *
     * @param \App\Models\Quotation $quotation
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Approve the material request.
     *
     */
    public function save()
    {
        $this->canSaved();
        $this->exceptionOnNoItems();

        $this->quotation->status = Quotation::SAVED;
        $this->quotation->saved_at = now();
        $this->quotation->saved_by_id = auth()->user()->id;
        $this->quotation->save();

        return $this->quotation;
    }

    /**
     * Can approve.
     *
     * @throws \Exception
     */
    public function canSaved()
    {
        if ($this->quotation->saved_at) {
            throw new \Exception('Quotation is already saved.');
        }

        return true;
    }

    /**
     * Throw an exception if no items are available.
     *
     * @throws \Exception
     */
    public function exceptionOnNoItems()
    {
        if ( ! $this->quotation->items()->count() ) {
            throw new \Exception('Quotation cannot be saved without items');
        }
    }
}
