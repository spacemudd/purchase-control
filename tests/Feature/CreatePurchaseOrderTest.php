<?php

namespace Tests\Feature;

use App\Clarimount\Service\PurchaseOrderService;
use App\Events\PurchaseOrderSaved;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersItem;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\Vendor;
use App\Models\Address;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePurchaseOrderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_creating_addresses()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-addresses',
        ]);

        $address = [
            'location' => $this->faker->streetAddress,
            'department' => $this->faker->companySuffix,
            'contact_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'type' => Address::SHIPPING,
        ];

        $url = route('addresses.store');
        $this->actingAs($user)->post($url, $address);

        $this->assertDatabaseHas('addresses', $address);

        $address['type'] = Address::BILLING;

        $url = route('addresses.store');
        $this->actingAs($user)->post($url, $address);

        $this->assertDatabaseHas('addresses', $address);
    }

    public function test_create_purchase_order()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-purchase-orders',
        ]);

        $supplier = factory(Vendor::class)->create();

        $shipping_address = factory(Address::class)->create([
            'type' => Address::SHIPPING,
        ]);

        $billing_address = factory(Address::class)->create([
            'type' => Address::BILLING,
        ]);

        $data = [
            'vendor_id' => $supplier->id,
            'shipping_address_id' => $shipping_address->id,
            'billing_address_id' => $billing_address->id,
            'currency' => $this->faker->currencyCode,
        ];

        $url = route('purchase-orders.store');
        $this->actingAs($user)->post($url, $data);

        /**
         * Re-fetching the records because the factory() doesn't necessarily return
         * the column names in-order. The JSON encoded string won't match by then.
         */
        $shipping_address = Address::where('id', $shipping_address->id)->firstOrFail()->toArray();
        $billing_address = Address::where('id', $billing_address->id)->firstOrFail()->toArray();
        $data['shipping_address_json'] = json_encode($shipping_address);
        $data['billing_address_json'] = json_encode($billing_address);

        $this->assertDatabaseHas('purchase_orders', $data);
    }

    public function test_totals_are_calculated()
    {
        $user = factory(User::class)->create();

        $po = factory(PurchaseOrder::class)->create(['status' => PurchaseOrder::NEW]);
        factory(PurchaseOrdersItem::class, 5)->create([
            'purchase_order_id' => $po->id,
        ]);

        $url = route('purchase-orders.save', ['id' => $po->id]);
        $this->actingAs($user)->post($url)->assertRedirect();

        $subTotal = 0;
        $taxAmount = 0;
        foreach($po->items()->get() as $item) {
            $subTotal += $item->subtotal_minor;
            $taxAmount += $item->tax_amount_1_minor;
        }

        $this->assertDatabaseHas('purchase_orders', [
            'subtotal_minor' => $subTotal,
            'tax_amount_1_minor' => $taxAmount,
            'total_minor' => $subTotal + $taxAmount,
        ]);

        $formattedTotal = number_format(($subTotal+$taxAmount) / 100, 2);
        $po = PurchaseOrder::find(1);
        $this->assertEquals($formattedTotal, $po->total);
    }

    /**
     * test_create_purchase_order originally required addresses and other
     * fields to be required.
     *
     * Everything will be null at initial creation except for currency.
     *
     */
    public function test_create_draft_purchase_order()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-purchase-orders',
        ]);

        $url = route('purchase-orders.store');
        $this->actingAs($user)
            ->post($url);

        $this->assertDatabaseHas('purchase_orders', [
            'created_by_id' => $user->id,
            'currency' => 'SAR', // The default currency.
        ]);
    }
}
