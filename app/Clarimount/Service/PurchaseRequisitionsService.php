<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Clarimount\Service;

use App\Models\MaxNumber;
use App\Models\RequestDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\PurchaseRequisitionsRepository;
use Illuminate\Support\Facades\View;

class PurchaseRequisitionsService
{
    protected $repository;

    public function __construct(PurchaseRequisitionsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store($data)
    {
        $this->validate($data)->validate();

        $data['created_by_id'] = auth()->user()->id;

        return $this->repository->store($data);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'requested_by_id' => 'required|exists:employees,id',
            'cost_center_by_id' => 'required|exists:departments,id',
            'requested_for_id' => 'nullable|exists:employees,id',
            'cost_center_for_id' => 'nullable|exists:departments,id',
        ]);
    }

    /**
     * Approves a request.
     *
     * @param $id
     * @return \App\Models\RequestDocument
     */
    public function approve($id)
    {
        return $this->repository->approve($id);
    }

    /**
     *
     * @param $id Request ID
     */
    public function sendToPurchasing($id)
    {
        $request = $this->repository->find($id);

        if($request->status != RequestDocument::UNSET) return abort(404);

        $request->status = RequestDocument::DRAFT;
        $request->save();

        // todo: send a notification.

        return $request;
    }

    public function delete($id)
    {
        if( ! $this->repository->find($id)->canAddItems) throw new \Exception('Requisition must be in draft mode.');

        return $this->repository->delete($id);
    }

    /**
     * Saves the purchase requisition (to become unmodifiable) for rejection/approval.
     *
     * @param $id
     * @return mixed
     */
    public function save($id)
    {
        $requisition = DB::transaction(function() use ($id) {
            $requisition = $this->repository->lockFind($id);

            if($requisition->status != (RequestDocument::DRAFT || RequestDocument::UNSET)) throw new \Exception('Requisition must be in draft mode');

            // Calculating the new request number.
            $numberPrefix = 'REQ-' . Carbon::now()->format('Y-m');
            $maxNumber = MaxNumber::lockForUpdate()->firstOrCreate([
                'name' => $numberPrefix,
            ], [
                'value' => 0,
            ]);

            $number = ++$maxNumber->value;

            // The updates.
            $requisition->number = $maxNumber->name . '-' . sprintf('%05d', $number);

            $requisition->status = RequestDocument::SAVED;
            $requisition->save();

            // Save the new number.
            $maxNumber->value = $number;
            $maxNumber->save();

            return $requisition;
        }, 2);

        // todo: notification.

        return $requisition;
    }

    /**
     * Print a requisition form in PDF format.
     *
     * @param $id
     * @return \Knp\Snappy\Pdf
     * @throws \Exception
     */
    public function pdf($id)
    {
        $data = $this->repository->find($id);

        // DB settings of PDF.
        // $marginTopDb = \App\Models\SystemPreference::where('slug', 'pdf-margin-top')->first();

        $pdf = \App::make('snappy.pdf.wrapper');

        $pdf->setOption('page-size', 'A4');
        $pdf->setOption('orientation', 'portrait');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('dpi', 300);
        $pdf->setOption('image-dpi', 300);
        $pdf->setOption('lowquality', false);
        $pdf->setOption('no-background', false);
        $pdf->setOption('enable-internal-links', true);
        $pdf->setOption('enable-external-links', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('no-background', false);
        // $pdf->setOption('margin-top', $marginTopDb ? $marginTopDb->value : 55);
        $pdf->setOption('margin-left', 5);
        $pdf->setOption('margin-right', 5);
        $pdf->setOption('margin-top', 55);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('disable-smart-shrinking', true);
        $pdf->setOption('zoom', 0.78);
        $pdf->setOption('header-html', $this->generateHeaderTempFile($data));
        $pdf->setOption('footer-html', resource_path('views/pdf/footer.html'));

        return $pdf->loadView('pdf.purchase-requisitions.form', compact('data'));
    }

    /**
     *
     * @param $data
     * @return bool|string
     * @throws \Exception
     */
    public function generateHeaderTempFile($data)
    {
        $content = View::make('pdf.purchase-requisitions.header', compact('data'))
            ->render();

        // '@' to suppress an exception that tempnam throws when it creates a file.
        $fileLocation = @tempnam(sys_get_temp_dir(), 'cla');
        rename($fileLocation, $fileLocation .= '.html');
        str_replace('.tmp', '.html', $fileLocation);

        $writeAttempt = File::put($fileLocation, $content);

        if(! $writeAttempt) {
            throw new \Exception('Failed writing to: ' . $fileLocation);
        }

        return $fileLocation;
    }
}
