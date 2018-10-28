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
use App\Model\PurchaseTerm;
use App\Models\CompanyJournal;
use App\Models\SalesTax;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\Address;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\PurchaseOrderRepository;
use Money\Currency;

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
     *
     * @param  int $id purchase order id
     * @return void
     * @throws \Exception
     */
	public function failIfNotDraft($id)
	{
		$status = PurchaseOrder::where('id', $id)->firstOrFail()->status;

		if($status != PurchaseOrder::NEW) {
		    throw new \Exception('A non-draft PO cannot be deleted');
		}
	}

	public function store(array $data)
	{
		$purchase_order = $data;

		if(array_key_exists('delivery_date', $data) && $data['delivery_date']) {
		    try {
		        Log::info('Attempting to parse: ' . $data['delivery_date']);
		        Log::info('Cleaned date: ' . str_replace('/','-', $data['delivery_date']));
                $purchase_order['delivery_date'] = Carbon::parse(str_replace('/','-', $data['delivery_date']));
            } catch (\Exception $error) {
		        Log::error('Failed parsing delivery date: ' . $data['delivery_date']);
            }
        }

        $this->validate($purchase_order)->validate();

        $purchase_order['status'] = PurchaseOrder::NEW;
        $purchase_order['date'] = now();

        if(isset($purchase_order['billing_address_id'])) {
            $purchase_order['billing_address_json'] = Address::where('id', $purchase_order['billing_address_id'])->firstOrFail();
        } else {
            $firstBillingAddress = Address::billing()->first();
            if($firstBillingAddress) {
                $purchase_order['billing_address_id'] = $firstBillingAddress->id;
                $purchase_order['billing_address_json'] = $firstBillingAddress;
            }
        }

        if(isset($purchase_order['shipping_address_id'])) {
            $purchase_order['shipping_address_json'] = Address::where('id', $purchase_order['shipping_address_id'])->firstOrFail();
        } else {
            $shippingAddress = Address::shipping()->first();
            if($shippingAddress) {
                $purchase_order['shipping_address_id'] = $shippingAddress->id;
                $purchase_order['shipping_address_json'] = $shippingAddress;
            }
        }

        if(isset($purchase_order['vendor_id'])) {
            $purchase_order['vendor_json'] = Vendor::where('id', $purchase_order['vendor_id'])->firstOrFail();;
        }

        if( ! array_key_exists('currency', $purchase_order)) {
            $purchase_order['currency'] = 'SAR';
        }

        $purchase_order['created_by_id'] = auth()->user()->id;

        $po = DB::transaction(function() use ($purchase_order) {
            $po = $this->repository->create($purchase_order);
            $this->saveTermsToPo($po->id);
            return $po;
        }, 2);

        return $po;
    }

    /**
     * Saves the terms to the PO.
     *
     * @param $id Purchase order ID.
     * @return \App\Models\PurchaseOrder
     */
	public function saveTermsToPo($id)
	{
        $currentAvailableTerms = PurchaseTerm::get();

        $confirmTermsInJson = [];
        foreach($currentAvailableTerms as $term) {
            $confirmTermsInJson[$term->type->name][] = [
                'value' => $term,
                'enabled' => $term->enabled,
            ];
        }

        $po = $this->repository->find($id);
        $po->terms_json = $confirmTermsInJson;
        $po->save();

        return $po;
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

        $po = DB::transaction(function() use ($purchase_order) {
            $po = $this->repository->save($purchase_order['id']);

            event(new PurchaseOrderSaved($po));

            return $po;
        });

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
            'vendor_id' => 'nullable|exists:vendors,id',
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
        $pdf->setOption('margin-top', 40);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('disable-smart-shrinking', true);
        $pdf->setOption('zoom', 0.78);
        $pdf->setOption('header-html', $this->generateHeaderTempFile($data));
        // $pdf->setOption('footer-html', resource_path('views/pdf/footer.html'));

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
            Log::info('Beginning to calculate PO totals');

            $po = PurchaseOrder::where('id', $id)->sharedLock()->firstOrFail();

            $total_subtotal = Money::of(0, $po->currency);
            $total_vat_amount = Money::of(0, $po->currency);
            $total = Money::of(0, $po->currency);

            Log::info('Calculating the items for PO ID: ' . $po->id);

            foreach($po->items()->get() as $item) {

                Log::info('Adding subtotal: ' . $item->subtotal);

                $total_subtotal = $total_subtotal->plus($item->subtotal);

                // Add the taxes.
                if($item->taxes) {
                    Log::info('Beginning to add taxes');
                    foreach($item->taxes as $tax) {
                        Log::info('Adding tax amount: ' . $tax->amount);
                        $total_vat_amount = $total_vat_amount->plus($tax->amount, RoundingMode::HALF_UP);

                        // Posting to the tax's account ID.
                        Log::info('Posting to the sales tax account with the minor amount: ' . $tax->amount);
                        $minorAmount = new \Money\Money($tax->amount, new Currency($po->currency));
                        $taxAccount = SalesTax::where('id', $tax->id)->firstOrFail()
                            ->company_journal;

                        Log::info('Preparing company journal to post: '. $taxAccount->id . ' - ' . $taxAccount->name);

                        $taxJournal = $taxAccount->journal;

                        Log::info('Preparing to debit the tax journal ID: ' . $taxJournal->id);

                        // TODO: Some error about debiting account because of IDENTITY COLUMN.
                        // DB::statement('SET IDENTITY_INSERT pur_accounting_journal_transactions ON');
                        // $taxJournal->debit($minorAmount, 'PO ' . $po->code);
                    }
                }

                Log::info('Adding item total: ' . $item->total);
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
