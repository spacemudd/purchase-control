<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\RequestDocument;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRequestTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_creating_a_request_document()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-requests',
            'send-requests-to-purchasing',
        ]);

        $byEmployee = factory(Employee::class)->create();
        $forEmployee = factory(Employee::class)->create();

        $url = route('api.requests.store');
        $data = [
            'requested_by_id' => $byEmployee->id,
            'cost_center_by_id' => $byEmployee->department->id,
            'requested_for_id' => $forEmployee->id,
            'cost_center_for_id' => $forEmployee->id,
        ];

        $this->actingAs($user)->post($url, $data)->assertSessionMissing('errors');

        $data['status'] = RequestDocument::UNSET; // See \App\Clarimount\Service\RequestDocument@store

        $this->assertDatabaseHas('request_documents', $data);
    }

    public function test_sending_to_purchasing_dept()
    {
        $requestDocument = factory(RequestDocument::class)->create([
            'status' => RequestDocument::UNSET,
        ]);

        $user = factory(User::class)->create()->givePermissionTo([
            'send-requests-to-purchasing'
        ]);

        $url = route('api-requests.send-to-purchasing', ['id' => $requestDocument->id]);

        $this->actingAs($user)->post($url)->assertSessionMissing('errors');

        $this->assertDatabaseHas('request_documents', [
            'id' => $requestDocument->id,
            'status' => RequestDocument::DRAFT,
        ]);

        // todo: assert notification.
    }
    
    public function test_request_sent_to_purchasing_dept_visibility()
    {
        $user = factory(User::class)->create();

        $requestDocument = factory(RequestDocument::class)->create([
            'status' => RequestDocument::UNSET,
        ]);

        $url = route('requests.by-status', ['status_slug' => 'draft']);

        $seeText = $requestDocument->requested_by->code . ' - ' . $requestDocument->requested_by->name;

        $this->actingAs($user)->get($url)->assertDontSeeText($seeText);

        $requestDocument->status = RequestDocument::DRAFT;
        $requestDocument->save();

        $this->actingAs($user)->get($url)->assertSeeText($seeText);
    }
}
