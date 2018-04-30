<?php

namespace Tests\Feature;

use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateNotesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_saving_note_on_purchase_requisition()
    {
        $user = factory(User::class)->create();

        $requisition = factory(PurchaseRequisition::class)->create();

        $note = [
            'id' => $requisition->id,
            'body' => $this->faker->paragraph,
        ];

        $url = route('api.purchase-requisition.notes');
        $this->actingAs($user)->post($url, $note)->assertSessionMissing('errors');

        $this->assertDatabaseHas('notes', [
            'body' => $note['body'],
            'user_id' => $user->id,
        ]);
    }
}
