<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSalesTaxesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_user_cannot_see_sales_taxes_if_no_permission()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get('/')
            ->assertDontSeeText('Sales Taxes');
    }

    public function test_user_can_see_sales_taxes_if_has_permission()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'view-sales-taxes',
        ]);

        $this->actingAs($user)->get('/')
            ->assertSee('Sales Taxes');
    }

    public function test_user_cannot_create_sales_tax_if_has_no_permission()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get('/settings/sales-taxes/create')
            ->assertStatus(403);
    }

    public function test_user_can_create_sales_tax_if_has_permission()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-sales-taxes',
        ]);

        $this->actingAs($user)->get('/settings/sales-taxes/create')
            ->assertStatus(200);
    }

    public function test_user_can_store_sales_tax()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-sales-taxes',
        ]);

        $this->actingAs($user)->post('/settings/sales-taxes/store', [
            'tax_name' => 'Goods and Services Tax',
            'abbreviation' => 'GST',
            'description' => '',
            'tax_number' => '',
            'current_tax_rate' => '5',
            'is_recoverable' => false,
            'is_compound' => false,
        ]);

        $this->assertDatabaseHas('sales_taxes', [
            'tax_name' => 'Goods and Services Tax',
            'abbreviation' => 'GST',
            'description' => '',
            'tax_number' => '',
            'current_tax_rate' => '5',
            'is_recoverable' => false,
            'is_compound' => false,
        ]);
    }
}
