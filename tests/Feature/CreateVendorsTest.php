<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateVendorsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_vendor_view_permission()
    {
        $url = route('vendors.index');

        $allowedUser = factory(User::class)->create()->givePermissionTo('view-vendor');
        $deniedUser = factory(User::class)->create();

        $this->actingAs($allowedUser)->get($url)->assertSuccessful();
        $this->actingAs($deniedUser)->get($url)->assertStatus(403);
    }

    public function test_vendor_create_permission()
    {
        $url = route('vendors.create');

        $allowedUser = factory(User::class)->create()->givePermissionTo('create-vendor');
        $deniedUser = factory(User::class)->create();

        $this->actingAs($allowedUser)->get($url)->assertSuccessful();
        $this->actingAs($deniedUser)->get($url)->assertStatus(403);
    }

    public function test_vendor_update_permission()
    {
        $vendor = factory(Vendor::class)->create();
        $url = route('vendors.update', ['id' => $vendor->id]);
        $data = ['name' => 'vendor_1'];

        $allowedUser = factory(User::class)->create()->givePermissionTo('update-vendor');
        $deniedUser = factory(User::class)->create();

        $this->actingAs($allowedUser)->put($url, $data)->assertSessionMissing('errors');
        $this->actingAs($deniedUser)->put($url, $data)->assertStatus(403);
    }

    public function test_vendor_delete_permission()
    {
        $vendor = factory(Vendor::class)->create();
        $url = route('vendors.destroy', ['id' => $vendor->id]);

        $allowedUser = factory(User::class)->create()->givePermissionTo('delete-vendor');
        $deniedUser = factory(User::class)->create();

        $this->actingAs($allowedUser)->delete($url)->assertSessionMissing('errors');
        $this->actingAs($deniedUser)->delete($url)->assertStatus(403);
    }

    public function test_saving_vendor()
    {
        $user = factory(User::class)->create()->givePermissionTo('create-vendor');

        $url = route('vendors.store');

        $data = [
            'name' => $this->faker->company,
            'established_at' => $this->faker->date(),
            'address' => $this->faker->streetAddress,
            'telephone_number' => $this->faker->phoneNumber,
            'fax_number' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'website' => $this->faker->domainName,
        ];

        $this->actingAs($user)->post($url, $data)->assertRedirect();

        $this->assertDatabaseHas('vendors', ['name' => $data['name']]);
    }
}
