<?php

namespace App\Classes;

use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use Excel;

/**
 * For -all- material requests.
 *
 * @package App\Classes
 */
class MaterialRequestExcelAll
{
    /**
     * @var string
     */
    protected $fileName = 'material-requests';

    protected $columns = [
        'Code',
        'Location',
        'Cost Center',
        'Item',
        'Quantity',
    ];

    /**
     *
     * @return \App\Classes\MaterialRequestExcelAll
     */
    static public function new(): MaterialRequestExcelAll
    {
        return new MaterialRequestExcelAll();
    }

    public function download()
    {
        $excel = Excel::create($this->fileName, function($excel) {
            $excel->sheet('Material Request', function ($sheet) {
                $sheet->appendRow($this->columns);

                // Download only pending material requests.
                MaterialRequest::pending()->each(function (MaterialRequest $request) use ($sheet) {
                    $request->items()->get()->each(function(MaterialRequestItem $item) use ($sheet, $request) {
                        $sheet->appendRow($this->itemForExcel($item, $request));
                    });
                });
            });
        });

        return $excel->download();
    }

    /**
     *
     * @param \App\Models\MaterialRequestItem $item
     * @param \App\Models\MaterialRequest $request
     * @return array
     */
    public function itemForExcel(MaterialRequestItem $item, MaterialRequest $request): array
    {
        return [
            $request->number,
            $request->location->name,
            $request->cost_center->display_name,
            $item->description,
            $item->qty,
        ];
    }
}
