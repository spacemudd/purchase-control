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

use App\Events\PurchaseOrderSaved;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\Address;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\PurchaseOrderRepository;

class PurchaseOrderService
{
	protected $repository;

	public function __construct(PurchaseOrderRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index()
	{
		return $this->repository->all();
	}

	public function paginatedIndex($per_page)
	{
		if($per_page > 100) {
			$per_page = 100;
		}

		if($per_page < 10) {
			$per_page = 10;
		}

		return $this->repository->paginatedIndex($per_page);
	}

	public function destroy($id)
	{
		$this->failIfNotDraft($id);
		return $this->repository->destroy($id);
	}

	/**
	 * Gives 404 if the purchase order is committed.
	 * @param  int $id purchase order id
	 * @return \Illuminate\Http\Response
	 */
	public function failIfNotDraft($id)
	{
		$status = PurchaseOrder::where('id', $id)->firstOrFail()->status;

		if($status != 'draft') {
			return abort(404);
		}
	}

	public function store()
	{
		$purchase_order = request()->except(['_token']);

		$this->validate($purchase_order)->validate();

        $purchase_order['status'] = PurchaseOrder::NEW;

        if(isset($purchase_order['billing_address_id'])) {
            $purchase_order['billing_address_json'] = Address::where('id', $purchase_order['billing_address_id'])->firstOrFail();
        }

        if(isset($purchase_order['shipping_address_id'])) {
            $purchase_order['shipping_address_json'] = Address::where('id', $purchase_order['shipping_address_id'])->firstOrFail();
        }

        if(isset($purchase_order['vendor_id'])) {
            $purchase_order['vendor_json'] = Vendor::where('id', $purchase_order['vendor_id'])->firstOrFail();;
        }

        $purchase_order['created_by_id'] = auth()->user()->id;



		return $this->repository->create($purchase_order);
	}

    public function attachments()
    {
        $request = request()->only('id');

        return $this->repository->attachments($request['id']);
    }

    public function downloadAttachment()
    {
        $request = request()->only('id');

        return $this->repository->downloadAttachment($request['id']);
    }

	public function edit($id)
	{
		return $this->repository->find($id);
	}

	public function update($id, array $purchaseOrder)
	{
		$this->validate($purchaseOrder)->validate();

		if(array_key_exists('date', $purchaseOrder) && $purchaseOrder['date']) {
            $purchaseOrder['date'] = Carbon::createFromFormat('d/m/Y', $purchaseOrder['date']);
        }

        if(array_key_exists('delivery_date', $purchaseOrder) && $purchaseOrder['delivery_date']) {
            $purchaseOrder['delivery_date'] = Carbon::createFromFormat('d/m/Y', $purchaseOrder['delivery_date']);
        }

		return $this->repository->update($id, $purchaseOrder);
	}

	public function show($id)
	{
		return $this->repository->find($id);
	}

	public function save($id=null)
    {
        // did the $id=null cause the API controller isn't injecting the save() w/ the id.
        // the http controller is... injecting.
        if($id) {
            $purchase_order = ['id' => $id];
        } else {
            $purchase_order = request()->only('id');
        }

        $this->validatePoExists($purchase_order)->validate();

        if( ! $this->isPoDraft($purchase_order['id'])) {
            throw new \Exception('The PO must be in draft mode to be saved.');
        }

        $po = $this->repository->save($purchase_order['id']);

        event(new PurchaseOrderSaved($po));

        return $po;
    }

    /**
     * Checks if the PO has the necessary fields in order to save it.
     *
     * @param $id
     * @return bool
     */
    public function isReadyToSave($id)
    {
        $po = $this->repository->find($id);

        if(!$po->delivery_date) return false;
        if(!$po->date) return false;
        if(!$po->vendor_id) return false;

        return true;
    }

    public function commit()
    {
        $purchase_order = request()->only('id');

        $this->validatePoExists($purchase_order)->validate();

        if( ! $this->isPoDraft($purchase_order['id'])) {
            return false;
        }

        return $this->repository->commit($purchase_order['id']);
    }

	public function void()
	{
		$purchase_order = request()->only('id');

		$this->validatePoExists($purchase_order)->validate();
		
		if( ! $this->isPoCommitted($purchase_order['id'])) {
			return false;
		}

		return $this->repository->void($purchase_order['id']);
	}

	public function validate(array $data)
	{
		$validator = Validator::make($data, [
            'vendor_id' => 'required|exists:vendors,id',
            'shipping_address_id' => 'nullable|exists:addresses,id',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'currency' => 'nullable|string|max:255',
        ]);

		return $validator;
	}

	public function validatePoExists(array $data)
	{
		return Validator::make($data, ['id' => 'required|exists:purchase_orders,id']);
	}

	/**
	 * Confirms Purchase Order is in draft status.
	 *
	 * @param  int  $id
	 * @return boolean
	 */
	public function isPoDraft($id)
	{
		return PurchaseOrder::where('id', $id)->firstOrFail()->isDraft;
	}

	/**
	 * Confirms Purchase Order is in void status.
	 *
	 * @param  int  $id
	 * @return boolean
	 */
	public function isPoCommitted($id)
	{
		return PurchaseOrder::where('id', $id)->firstOrFail()->isCommitted;
	}

	public function updateHistoricalData($id)
	{
        $po = $this->repository->find($id);

        if(isset($po['billing_address_id'])) {
            $po->billing_address_json = Address::where('id', $po->billing_address_id)->firstOrFail();
        } else {
            $po->billing_address_json = null;
        }

        if(isset($po['shipping_address_id'])) {
            $po->shipping_address_json = Address::where('id', $po->shipping_address_id)->firstOrFail();
        } else {
            $po->shipping_address_json = null;
        }

        if(isset($po['vendor_id'])) {
            $po->vendor_json = Vendor::where('id', $po->vendor_id)->firstOrFail();;
        } else {
            $po->vendor_json = null;
        }

        $po->save();
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
        $pdf->setOption('margin-top', 35);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('disable-smart-shrinking', true);
        $pdf->setOption('zoom', 0.78);
        $pdf->setOption('header-html', $this->generateHeaderTempFile($data));
        $pdf->setOption('footer-html', resource_path('views/pdf/footer.html'));

        return $pdf->loadView('pdf.purchase-orders.form', compact('data'));
    }

    /**
     *
     * @param $data
     * @return bool|string
     * @throws \Exception
     */
    public function generateHeaderTempFile($data)
    {
        $content = View::make('pdf.purchase-orders.header', compact('data'))
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

    /**
     * Calculates the subtotals, tax, and totals and saves them to the PO header.
     *
     * @param $id Purchase Order ID.
     * @return mixed
     */
    public function calculateAndSavePurchaseOrderCosts($id)
    {
        $po = DB::transaction(function() use ($id) {
            $po = PurchaseOrder::where('id', $id)->sharedLock()->firstOrFail();

            $total_subtotal = Money::of(0, $po->currency);
            $total_vat_amount = Money::of(0, $po->currency);
            $total = Money::of(0, $po->currency);

            foreach($po->items()->get() as $item) {
                $total_subtotal = $total_subtotal->plus($item->subtotal);
                if($item->tax_amount_1) {
                    $total_vat_amount = $total_vat_amount->plus($item->tax_amount_1);
                }
                $total = $total->plus($item->total);
            }

            $po->subtotal_minor = $total_subtotal->getMinorAmount()->toInt();
            $po->tax_amount_1_minor = $total_vat_amount->getMinorAmount()->toInt();
            $po->total_minor = $total->getMinorAmount()->toInt();
            $po->save();

            return $po;
        }, 2);

        return $po;
    }
}
