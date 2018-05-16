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
              factory(PurchaseOrdersItem::class, 15)->create(['purchase_order_id' => $po->id]);

        $url = route('purchase-orders.save', ['id' => $po]);
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

        $this->assertDatabaseHas('purchase_orders', [
            'purchase_order_main_id' => $po->id,
            'vendor_id' => $vendor_2->id,
        ]);
    }

    /**
     * This tests the generation of incremental letters (A..B) of multi-vendor sub purchase orders.
     *
     */
    public function test_saving_multi_vendor_sub_po_first_letter()
    {
        $user = factory(User::class)->create();

        $po = factory(PurchaseOrder::class)->create([
            'vendor_id' => factory(Vendor::class)->create()->id,
            'status' => PurchaseOrder::SAVED,
            'number' => 'PO-2018-05-00002',
            ]);

        $subPo = factory(PurchaseOrder::class)->create([
            'purchase_order_main_id' => $po->id,
            'vendor_id' => factory(Vendor::class)->create()->id, // A different vendor than the main PO's one.
            'number' => null,
        ]);
        $subPo->each(function($subPo) {
            factory(PurchaseOrdersItem::class, 2)->create(['purchase_order_id' => $subPo->id]);
        });

        $url = route('purchase-orders.sub.save', ['purchase_order_id' => $po->id, 'id' => $subPo->id]);
        $this->actingAs($user)->post($url);

        // Test first lettering.
        $this->assertDatabaseHas('purchase_orders', [
            'id' => $subPo->id,
            'number' => $po->number.'-A',
        ]);
    }

    public function test_saving_multi_vendor_sub_po_increment_letters()
    {
        $user = factory(User::class)->create();

        $mainPo = factory(PurchaseOrder::class)->create(['number' => 'PO-2018-05-00002']);

        $subPo = factory(PurchaseOrder::class)->create([
            'purchase_order_main_id' => $mainPo->id,
            'vendor_id' => factory(Vendor::class)->create()->id,
            'number' => 'PO-2018-05-00002-A',
        ]);

        $lettersToMake = 10;
        $letter = 'A';
        for($i=0; $i<$lettersToMake; ++$i) {
            $subPo_2 = factory(PurchaseOrder::class)->create([
                'purchase_order_main_id' => $mainPo->id,
                'vendor_id' => factory(Vendor::class)->create()->id,
                'number' => null,
            ]);

            $url = route('purchase-orders.sub.save', ['purchase_order_id' => $subPo_2->purchase_order_main_id, 'id' => $subPo->id]);
            $this->actingAs($user)->post($url);

            $this->assertDatabaseHas('purchase_orders', [
                'id' => $subPo->id,
                'number' => 'PO-2018-05-00002-'.++$letter,
            ]);
        }
    }
}
