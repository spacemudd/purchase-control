<?php

namespace Tests\Feature;

use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUploadsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_saving_file_to_purchase_requisition()
    {
        $user = factory(User::class)->create();

        $requisition = factory(PurchaseRequisition::class)->create();

        $url = route('api.purchase-requisition.uploads', ['id' => $requisition->id]);

        $this->actingAs($user)->post($url, [
            'id' => (string) $requisition->id,
            'files' => [
                UploadedFile::fake()->create('document-1.pdf', 1024 * 24),
                UploadedFile::fake()->create('document-2.pdf', 1024 * 24),
            ],
        ])->assertSuccessful();

        $uploadedFilesCount = PurchaseRequisition::find($requisition->id)->media()->count();

        $this->assertEquals(2, $uploadedFilesCount, 'The uploaded files are not equal in count');
    }
}
