<?php

namespace Tests\Feature;

use App\Models\PurchaseOrder;
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
                'enabled' => false,
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

    public function test_saving_terms()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['create-purchase-orders']);

        $this->actingAs($user)->post(route('purchase-orders.store'), [
            'currency' => 'SAR',
        ])->assertRedirect();

        $po = PurchaseOrder::where('id', 1)->firstOrFail();

        $internalTerm = PurchaseTerm::find(3);

        $url = route('api.purchase-orders.terms.attach');
        $this->actingAs($user)->post($url, [
            'purchase_order_id' => $po->id,
            'term_id' => $internalTerm->id,
        ])->assertSuccessful();

        $po->refresh();

        $termToAssert = null;
        foreach($po->terms_json as $type => $terms) {
            foreach($terms as $term) {
                if($term->value->id == $internalTerm->id) {
                    $termToAssert = $term;
                    break;
                }
            }
        }

        $this->assertTrue($termToAssert->enabled);
    }

    public function test_removing_term()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['create-purchase-orders']);

        $this->actingAs($user)->post(route('purchase-orders.store'), [
            'currency' => 'SAR',
        ])->assertRedirect();

        $po = PurchaseOrder::where('id', 1)->firstOrFail();

        $internalTerm = PurchaseTerm::find(3);

        $url = route('api.purchase-orders.terms.attach');
        $this->actingAs($user)->post($url, [
            'purchase_order_id' => $po->id,
            'term_id' => $internalTerm->id,
        ])->assertSuccessful();

        $url = route('api.purchase-orders.terms.detach');
        $this->actingAs($user)->post($url, [
            'purchase_order_id' => $po->id,
            'term_id' => $internalTerm->id,
        ])->assertSuccessful();

        $po->refresh();

        $termToAssert = null;
        foreach($po->terms_json as $type => $terms) {
            foreach($terms as $term) {
                if($term->value->id == $internalTerm->id) {
                    $termToAssert = $term;
                    break;
                }
            }
        }

        $this->assertFalse($termToAssert->enabled);
    }
}
