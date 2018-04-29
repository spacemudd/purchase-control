<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDocumentItem extends Model
{
    protected $fillable = [
        'request_document_id',
        'item_template_id',
        'code',
        'name',
        'model_number',
        'manufacturer',
        'description',
        'qty',
    ];

    public function request()
    {
        return $this->belongsTo(RequestDocument::class);
    }

    public function requisition()
    {
        return $this->belongsTo(RequestDocument::class, 'request_document_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
