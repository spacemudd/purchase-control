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

        $this->actingAs($user)->get('/dashboard')
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
}
