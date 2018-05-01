<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\PurchaseRequisition;
use App\Models\User;
use App\Notifications\PurchaseRequisitionApprovedNotification;
use App\Notifications\PurchaseRequisitionSavedNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePurchaseRequisitionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_creating_a_purchase_requisition()
    {
        $user = factory(User::class)->create()->givePermissionTo([
            'create-purchase-requisitions',
            'send-purchase-requisitions-to-purchasing',
        ]);

        $byEmployee = factory(Employee::class)->create();
        $forEmployee = factory(Employee::class)->create();

        $url = route('purchase-requisitions.store');
        $data = [
            'requested_by_id' => $byEmployee->id,
            'cost_center_by_id' => $byEmployee->department->id,
            'requested_for_id' => $forEmployee->id,
            'cost_center_for_id' => $forEmployee->id,
        ];

        $this->actingAs($user)->post($url, $data)->assertSessionMissing('errors');

        $data['status'] = PurchaseRequisition::DRAFT; // See \App\Clarimount\Service\PurchaseRequisition@store

        $this->assertDatabaseHas('purchase_requisitions', $data);
    }

    public function test_sending_to_purchasing_dept()
    {
        $requestDocument = factory(PurchaseRequisition::class)->create([
            'status' => PurchaseRequisition::UNSET,
        ]);

        $user = factory(User::class)->create()->givePermissionTo([
            'send-purchase-requisitions-to-purchasing'
        ]);

        $url = route('api-purchase-requisitions.send-to-purchasing', ['id' => $requestDocument->id]);

        $this->actingAs($user)->post($url)->assertSessionMissing('errors');

        $this->assertDatabaseHas('purchase_requisitions', [
            'id' => $requestDocument->id,
            'status' => PurchaseRequisition::DRAFT,
        ]);

        // todo: assert notification.
    }
    
    public function test_request_sent_to_purchasing_dept_visibility()
    {
        $user = factory(User::class)->create();

        $requestDocument = factory(PurchaseRequisition::class)->create([
            'status' => PurchaseRequisition::UNSET,
        ]);

        $url = route('purchase-requisitions.by-status', ['status_slug' => 'draft']);

        $seeText = $requestDocument->requested_by->code . ' - ' . $requestDocument->requested_by->name;

        $this->actingAs($user)->get($url)->assertDontSeeText($seeText);

        $requestDocument->status = PurchaseRequisition::DRAFT;
        $requestDocument->save();

        $this->actingAs($user)->get($url)->assertSeeText($seeText);
    }

    public function test_subscribing_to_a_requisition()
    {
        Notification::fake();

        $notifiedUser = factory(User::class)->create()->givePermissionTo([
            'receive-purchase-requisitions-notifications',
        ]);

        // A user without the permission.
        $noPermissionUser = factory(User::class)->create();

        // This user won't receive the notification because they are the creator / the one to trigger 'save'.
        $user = factory(User::class)->create()->givePermissionTo([
            'create-purchase-requisitions',
            'receive-purchase-requisitions-notifications',
        ]);

        // Construct the creating request request.
        $url = route('purchase-requisitions.store');
        $byEmployee = factory(Employee::class)->create();
        $this->actingAs($user)->post($url, [
            'requested_by_id' => $byEmployee->id,
            'cost_center_by_id' => $byEmployee->department->id,
        ]);

        $this->assertDatabaseHas('purchase_requisitions_subscribers', [
            'user_id' => $notifiedUser->id,
        ]);

        $this->assertDatabaseMissing('purchase_requisitions_subscribers', [
            'user_id' => $noPermissionUser->id,
        ]);

        $saveUrl = route('purchase-requisitions.save', ['id' => 1]);
        $this->actingAs($user)->post($saveUrl)->assertRedirect();

        Notification::assertSentTo(
            $notifiedUser,
            PurchaseRequisitionSavedNotification::class,
            function($notification, $channels) {
                return $notification->purchaseRequisition->id === 1;
            }
        );
    }

    public function test_approving_purchase_requisition()
    {
        Notification::fake();

        $user = factory(User::class)->create()->givePermissionTo([
            'approve-purchase-requisitions',
            'decline-purchase-requisitions',
        ]);

        $requisition = factory(PurchaseRequisition::class)->create([
            'status' => PurchaseRequisition::SAVED,
        ]);

        $this->actingAs($user)->post(route('api.purchase-requisitions.subscribe', ['id' => $requisition->id]))->assertSuccessful();

        $url = route('purchase-requisitions.approve', ['id' => $requisition]);
        $this->actingAs($user)->post($url)->assertRedirect();

        Notification::assertSentTo(
            $user,
            PurchaseRequisitionApprovedNotification::class,
            function($notification, $channels) {
                return $notification->purchaseRequisition->id === 1;
            }
        );
    }
}
