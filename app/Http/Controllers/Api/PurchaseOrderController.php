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

namespace App\Http\Controllers\Api;

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Clarimount\Service\PurchaseOrderService;

class PurchaseOrderController extends Controller
{
    protected $service;

    public function __construct(PurchaseOrderService $service)
    {
    	$this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function paginatedIndex($per_page = 10)
    {
        return $this->service->paginatedIndex($per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @see \App\Clarimount\Service\PurchaseOrderService@store
     */
    public function store()
    {
        return $this->service->store();
    }

    public function update($id)
    {
        $data = request()->except(['_token', '_method']);

        return $this->service->update($id, $data);
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $purchaseOrders = PurchaseOrder::where('number', 'LIKE', '%' . $search . '%')
                                ->orWhere('id', 'LIKE', '%' . $search . '%');

        if($datePeriod = $this->parseDatePeriodDates()) {
            $purchaseOrders->whereBetween('date', $datePeriod);
        }

        //$purchaseOrders->orWhereHas('items', function($query) use ($search) {
        //    $query->where('manufacturer_serial_number', 'LIKE', '%' . $search . '%');
        //});

        $purchaseOrders->with('employee');

        return $purchaseOrders->paginate(15);
    }

    public function parseDatePeriodDates()
    {
        if(request()->has('datefrom') && request()->has('dateto')) {
            $datePeriod = [
                Carbon::parse(request()->input('datefrom'))->toDateTimeString(),
                Carbon::parse(request()->input('dateto'))->toDateTimeString(),
            ];

            return $datePeriod;
        }

        return;
    }

    public function show(Request $request)
    {
        return $this->service->show($request->only('id'));
    }

    /**
     * Commits a purchase order.
     *
     * @return bool
     */
    public function commit()
    {
        return $this->service->commit();
    }

    /**
     * Saves a purchase order.
     *
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        return $this->service->save();
    }

    /**
     * Voids a purchase order.
     *
     * @return bool
     */
    public function void()
    {
        return $this->service->void();
    }

    public function attachments()
    {
        return $this->service->attachments();
    }

    public function downloadAttachment()
    {
        return $this->service->downloadAttachment();
    }

    public function updateTokens($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $data[$request->name] = $request->value;

        $po = PurchaseOrder::where('id', $id)->firstOrFail();

        $po->update($data);

        return $po;
    }
}
