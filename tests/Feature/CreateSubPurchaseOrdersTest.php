<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use App\Models\User;
use App\Models\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSubPurchaseOrdersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_create_sub_purchase_order()
    {
        $user = factory(User::class)->create();

        $shipping_address = factory(Address::class)->create(['type' => Address::SHIPPING]);

        $billing_address = factory(Address::class)->create(['type' => Address::BILLING]);

        $vendor_1 = factory(Vendor::class)->create();
        $po = factory(PurchaseOrder::class)->create(['vendor_id' => $vendor_1->id, 'status' => PurchaseOrder::NEW]);
        $item = factory(PurchaseOrdersItem::class, 15)->create(['purchase_order_id' => $po->id]);
        $url = route('purchase-orders.save', ['id' => $po]); // So the number is generated.
        $this->actingAs($user)->post($url)->assertSessionMissing('errors');

        $vendor_2 = factory(Vendor::class)->create();
        $data = [
            'vendor_id' => $vendor_2->id,
            'shipping_address_id' => $shipping_address->id,
            'billing_address_id' => $billing_address->id,
            'currency' => $this->faker->currencyCode,
        ];

        $url = route('purchase-orders.sub.store', ['id' => $po->id]);
        $this->actingAs($user)->post($url, $data)->assertSessionMissing('errors');

        // Assert the PO has started '-A', '-B'.
        $lettering = 'A';
        $this->assertDatabaseHas('purchase_orders', [
            'purchase_order_main_id' => $po->id,
            'vendor_id' => $vendor_2->id,
            //'number' => $po->number . '-' . $lettering,
        ]);
    }
}
