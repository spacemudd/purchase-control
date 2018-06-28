<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use App\Model\PurchaseTermsType;
use App\Model\PurchaseTerm;
use App\Models\Address;
use App\Models\Vendor;
use App\Models\User;
use Tests\TestCase;

class CreatePurchaseOrderTermsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_user_can_see_terms_from_settings()
    {
        factory(PurchaseTermsType::class)->create()->each(function($termType) {
            factory(PurchaseTerm::class)->create([
                'type_id' => $termType->id,
            ]);
        });

        $term = PurchaseTerm::first()->value;

        $user = factory(User::class)->create()->givePermissionTo([
            'create-po-terms',
            'view-po-terms',
            'edit-po-terms',
            'delete-po-terms',
        ]);

        $url = route('purchasing-terms.index');

        $this->actingAs($user)->get($url)
            ->assertSuccessful()
            ->assertSeeText($term);
    }

    public function test_saving_terms_with_booleans_on_creating_po()
    {
        factory(PurchaseTermsType::class)->create()->each(function($termType) {
            factory(PurchaseTerm::class)->create([
                'type_id' => $termType->id,
            ]);
        });

        $user = factory(User::class)->create()->givePermissionTo([
            'create-purchase-orders',
        ]);

        $supplier = factory(Vendor::class)->create();
        $shipping_address = factory(Address::class)->create(['type' => Address::SHIPPING]);
        $billing_address = factory(Address::class)->create(['type' => Address::BILLING]);
        $data = [
            'vendor_id' => $supplier->id,
            'shipping_address_id' => $shipping_address->id,
            'billing_address_id' => $billing_address->id,
            'currency' => $this->faker->currencyCode,
        ];

        $url = route('purchase-orders.store');
        $this->actingAs($user)->post($url, $data);

        $confirmTermsInJson = [];
        $terms = PurchaseTerm::get();
        foreach($terms as $term) {
            $confirmTermsInJson[$term->type->name][] = [
                'value' => $term,
                'on' => false,
                ];
        }

        $this->assertDatabaseHas('purchase_orders', [
            'terms_json' => json_encode($confirmTermsInJson),
        ]);
    }

    /**
     *
     * @return void
     */

    //public function test_saving_terms()
    //{
    //    $user = factory(User::class)->create();
    //
    //    $term_type = PurchaseTermsType::firstOrCreate([
    //        'name' => 'Payment Terms',
    //        'order' => 1,
    //    ], [
    //        'name' => 'Payment Terms',
    //    ]);
    //
    //    $term = PurchaseTerm::create([
    //        'type_id' => $term_type->id,
    //        'value' => 'Upon Delivery',
    //    ]);
    //
    //    $po = factory(PurchaseOrder::class)->create();
    //
    //    $url = route('api.purchase-orders.terms.attach');
    //    $this->actingAs($user)->post($url, [
    //        'purchase_order_id' => $po->id,
    //        'term_id' => $term->id,
    //    ])->assertSuccessful();
    //
    //    $terms = $po->terms()->get();
    //
    //    $confirmTermsInJson = [];
    //    foreach($terms as $term) {
    //        $confirmTermsInJson[$term->type->name][] = $term;
    //    }
    //
    //    $this->assertDatabaseHas('purchase_orders', [
    //        'terms_json' => json_encode($confirmTermsInJson),
    //    ]);
    //
    //    // The removing part.
    //    $url = route('api.purchase-orders.terms.detach');
    //    $this->actingAs($user)->post($url, [
    //        'purchase_order_id' => $po->id,
    //        'term_id' => $term->id,
    //    ])->assertSuccessful();
    //
    //    $terms = $po->terms()->get();
    //
    //    $confirmTermsInJson = [];
    //    foreach($terms as $term) {
    //        $confirmTermsInJson[$term->type->name][] = $term;
    //    }
    //
    //    $this->assertDatabaseHas('purchase_orders', [
    //        'terms_json' => json_encode($confirmTermsInJson),
    //    ]);
    //}
}
