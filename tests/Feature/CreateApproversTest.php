<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateApproversTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function test_saving_an_approver()
    {
        $financial_auth = $this->faker->randomNumber(4);

        $user = factory(User::class)->create()->givePermissionTo([
            'create-approvers',
            'view-approvers',
            'edit-approvers',
            'delete-approvers',
        ]);

        $employee = factory(Employee::class)->create();

        $url = route('api.approvers.store');
        $response = $this->actingAs($user)->post($url, [
            'employee_id' => $employee->id,
            'financial_auth' => $financial_auth,
            'designation' => $this->faker->jobTitle,
        ]);

        $response->assertJson([
            'id' => $employee->id,
            'financial_auth' => $financial_auth,
        ]);
    }
}
