<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateVendorBankTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_saving_vendor_bank()
    {
        $user = factory(User::class)->create()->givePermissionTo('create-vendor-bank');

        $vendor = factory(Vendor::class)->create();

        $url = route('vendor-bank.store', ['id' => $vendor]);

        $data = [
            'vendor_id' => $vendor->id,
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'beneficiary_name' => $this->faker->company,
            'account_number' => $this->faker->bankAccountNumber,
            'iban_code' => $this->faker->iban('KSA'),
            'swift_code' => $this->faker->swiftBicNumber,
            'sort_code' => (string) $this->faker->randomNumber(6),
            'currency' => $this->faker->currencyCode,
        ];

        $this->actingAs($user)->post($url, $data)->assertSessionMissing('errors');

        $this->assertDatabaseHas('vendor_banks', $data);
    }
}
