<?php

namespace App\Http\Controllers\Back;

use App\Clarimount\Service\PurchaseOrderService;
use App\Clarimount\Service\SubPurchaseOrdersService;
use App\Clarimount\Service\VendorBankService;
use App\Model\PurchaseTermsType;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;

class PurchaseOrdersSubController extends Controller
{
    protected $service;
    protected $vendorBankService;
    protected $poService;

    public function __construct(SubPurchaseOrdersService $service, VendorBankService $vendorBankService, PurchaseOrderService $poService)
    {
        $this->service = $service;
        $this->vendorBankService = $vendorBankService;
        $this->poService = $poService;
    }

    /**
     * Returns a list of the sub purchase orders.
     *
     * @param $purchase_order_id Main PO ID.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::where('id', $purchase_order_id)->with('sub_purchase_orders')->firstOrFail();

        return view('sub-purchase-orders.index', compact('purchaseOrder'));
    }

    public function create($purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::find($purchase_order_id);

        $currencies = $this->vendorBankService->currencies();

        return view('sub-purchase-orders.create', compact('purchaseOrder', 'currencies'));
    }

    /**
     * Create a new sub PO.
     *
     * @param $purchase_order_id
     * @return mixed
     */
    public function store($purchase_order_id)
    {
        $data = request()->except('_token');

        $subPo = $this->service->store($data, $purchase_order_id);

        return redirect()->route('purchase-orders.sub.show', ['purchase_order_id' => $purchase_order_id,'id' => $subPo->id]);
    }

    /**
     * Generates a new sub PO number.
     *
     * @param $purchase_order_id
     * @param $id
     * @return mixed
     */
    public function save($purchase_order_id, $id)
    {
        // todo: make this api/json friendly.
        if(!$this->poService->isReadyToSave($id)) {
            session()->flash('status', 'is-warning');
            session()->flash('messages', ['Purchase order has missing tokens. Fields must be completed first.']);
            return redirect()->back();
        }

        if(!$this->poService->show($id)->items()->count()) {
            session()->flash('status', 'is-warning');
            session()->flash('messages', ['There are no items attached.']);
            return redirect()->back();
        }

        $subPo = $this->service->save($id);

        return redirect()->route('purchase-orders.sub.show', ['purchase_order_id' => $purchase_order_id, 'id' => $subPo->id]);
    }

    public function show($purchase_order_id, $id)
    {
        $subPurchaseOrder = PurchaseOrder::where('id', $id)
            ->where('purchase_order_main_id', '!=', null)
            ->with('main_purchase_order')
            ->firstOrFail();

        $purchaseTermsTypes = PurchaseTermsType::get();

        return view('sub-purchase-orders.show', compact('subPurchaseOrder', 'purchaseTermsTypes'));
    }
}
