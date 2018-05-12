<?php

namespace Tests\Feature;

use App\Models\PurchaseOrder;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePurchaseOrderTermsTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_saving_terms()
    {
        $user = factory(User::class)->create();

        $term_type = PurchaseTermsTypes::firstOrCreate([
            'name' => 'Payment Terms',
            'order' => 1,
        ], [
            'name' => 'Payment Terms',
        ]);

        $term = PurchaseTerms::create([
            'type_id' => $term_type->id,
            'value' => 'Upon Delivery',
        ]);

        $po = factory(PurchaseOrder::class)->create();

        $url = route('api.purchase-orders.attach-term');
        $this->actingAs($user)->post($url, [
            'purchase_order_id' => $po->id,
            'term_id' => 1,
        ]);

        $this->assertDatabaseHas('purchase_orders', [
            'terms' => json_encode([1 => [
                'name' => 'Payment Terms',
                'terms' => $term,
            ]])
        ]);
    }
}
