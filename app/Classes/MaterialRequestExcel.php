<?php

namespace App\Classes;

use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use Excel;

class MaterialRequestExcel
{
    /**
     * @var
     */
    protected $materialRequest;

    /**
     * @var string
     */
    protected $fileName;

    protected $columns = [
        'Code',
        'Location',
        'Cost Center',
        'Item',
        'Quantity',
    ];

    /**
     * MaterialRequestExcel constructor.
     *
     * @param \App\Models\MaterialRequest $materialRequest
     */
    public function __construct(MaterialRequest $materialRequest)
    {
        $this->materialRequest = $materialRequest;
        $this->fileName = str_slug($this->materialRequest->number);

    }

    /**
     *
     * @param $id
     * @return \App\Classes\MaterialRequestExcel
     */
    static public function find($id): MaterialRequestExcel
    {
        $materialRequest = MaterialRequest::find($id);
        return new MaterialRequestExcel($materialRequest);
    }

    public function download()
    {
        $excel = Excel::create($this->fileName, function($excel) {
            $excel->sheet('Material Request', function ($sheet) {
                $sheet->appendRow($this->columns);
                $this->materialRequest->items()->get()->each(function(MaterialRequestItem $item) use ($sheet) {
                    $sheet->appendRow($this->itemForExcel($item));
                });
            });
        });

        return $excel->download();
    }

    /**
     *
     * @param \App\Models\MaterialRequestItem $item
     * @return array
     */
    public function itemForExcel(MaterialRequestItem $item): array
    {
        return [
            $this->materialRequest->number,
            $this->materialRequest->location->name,
            $this->materialRequest->cost_center->display_name,
            $item->description,
            $item->qty,
        ];
    }
}
