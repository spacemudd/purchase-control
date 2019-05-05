<?php

namespace App\Classes;

use App\Models\MaterialRequest;

class MaterialRequestApproval
{
    /**
     * @var \App\Models\MaterialRequest 
     */
    private $materialRequest;

    static public function new(MaterialRequest $materialRequest): MaterialRequestApproval
    {
        return new MaterialRequestApproval($materialRequest);
    }

    /**
     * MaterialRequestApproval constructor.
     *
     * @param \App\Models\MaterialRequest $materialRequest
     */
    public function __construct(MaterialRequest $materialRequest)
    {
        $this->materialRequest = $materialRequest;
    }

    /**
     * Approve the material request.
     *
     */
    public function save()
    {
        $this->canApprove();
        $this->exceptionOnNoItems();

        $this->materialRequest->status = MaterialRequest::APPROVED;
        $this->materialRequest->approved_at = now();
        $this->materialRequest->approved_by_id = auth()->user()->id;
        $this->materialRequest->save();

        return $this->materialRequest;
    }

    /**
     * Can approve.
     *
     * @throws \Exception
     */
    public function canApprove()
    {
        if ($this->materialRequest->approved_at) {
            throw new \Exception('Material request already approved.');
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
        if ( ! $this->materialRequest->items->count() ) {
            throw new \Exception('Material request cannot be approved without items');
        }
    }
}
