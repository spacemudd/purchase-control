<?php

namespace Tests\Feature;

use App\Models\User;
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
}
