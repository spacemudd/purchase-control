<?php

namespace Tests\Feature;

use App\Models\ItemTemplate;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisitionItem;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePurchaseOrderItemsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_item_info_is_saved()
    {
        $user = factory(User::class)->create();

        $po = factory(PurchaseOrder::class)->create();

        $req_item = factory(PurchaseRequisitionItem::class)->create();

        $data = [
            'pr_item_id' => $req_item->id,
        ];

        $url = route('api.purchase-orders.requisition-items', ['purchase_order_id' => $po->id]);

        $this->actingAs($user)->post($url, $data);

        $this->assertDatabaseHas('purchase_orders_items', [
            'pr_item_id' => $req_item->id,
            'description' => $req_item->name,
            'qty' => 1,
            'unit_price_minor' => $req_item->item_template->default_unit_price_minor,
        ]);
    }
}
