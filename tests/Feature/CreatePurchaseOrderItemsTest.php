<?php

namespace Tests\Feature;

use App\Models\ItemTemplate;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisitionItem;
use App\Models\User;
use Brick\Money\Money;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePurchaseOrderItemsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
            'qty' => $this->faker->numberBetween(0, 10),
            'unit_price' => $this->faker->numberBetween(0, 4000),
            'discount' => 0.00,
            'tax_rate1' => 5, // is %
        ];

        $url = url('api/v1/purchase-orders/'.$po->id.'/requisition-items');

        $this->actingAs($user)->post($url, $data)->assertSuccessful();

        $tax_amount = Money::of($data['unit_price'], 'SAR')
            ->multipliedBy($data['qty'])
            ->multipliedBy($data['tax_rate1']/100);

        $this->assertDatabaseHas('purchase_orders_items', [
            'pr_item_id' => $req_item->id,
            'description' => $req_item->name,
            'qty' => $data['qty'],
            // 'unit_price_minor' => $req_item->item_template->default_unit_price_minor, // do a 'saving default price'
            'unit_price_minor' => Money::of($data['unit_price'], 'SAR')->getMinorAmount()->toInt(),
            'total_minor' => Money::of($data['unit_price'], 'SAR')
                ->multipliedBy($data['qty'])
                ->plus($tax_amount)
                ->getMinorAmount()
                ->toInt(),
        ]);
    }
}
