<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class CreateVendorTest extends DuskTestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_saving_vendor()
    {
        $user = factory(User::class)->create()->givePermissionTo('create-vendor-bank');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('login')
                ->type('username', $user->username)
                ->type('password', $user->password)
                ->press('Login');
        });
    }
}
