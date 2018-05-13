<?php

namespace Tests\Feature;

use App\Model\PurchaseTerm;
use App\Model\PurchaseTermsType;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePurchaseOrderTermsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    /**
     *
     * @return void
     */
    public function test_saving_terms()
    {
        $user = factory(User::class)->create();

        $term_type = PurchaseTermsType::firstOrCreate([
            'name' => 'Payment Terms',
            'order' => 1,
        ], [
            'name' => 'Payment Terms',
        ]);

        $term = PurchaseTerm::create([
            'type_id' => $term_type->id,
            'value' => 'Upon Delivery',
        ]);

        $po = factory(PurchaseOrder::class)->create();

        $url = route('api.purchase-orders.terms.attach');
        $this->actingAs($user)->post($url, [
            'purchase_order_id' => $po->id,
            'term_id' => $term->id,
        ])->assertSuccessful();

        $terms = $po->terms()->get();

        $this->assertDatabaseHas('purchase_orders', [
            'terms_json' => json_encode($terms),
        ]);

        // The removing part.
        $url = route('api.purchase-orders.terms.detach');
        $this->actingAs($user)->post($url, [
            'purchase_order_id' => $po->id,
            'term_id' => $term->id,
        ])->assertSuccessful();

        $terms = $po->terms()->get();

        $this->assertDatabaseHas('purchase_orders', [
            'terms_json' => json_encode($terms),
        ]);
    }
}
