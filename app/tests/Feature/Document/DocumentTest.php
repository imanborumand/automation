<?php

namespace App\tests\Feature\Document;

use App\Enums\User\Roles;
use App\Models\Document\Document;
use App\Models\Position\Position;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;


    /**
     *
     * @test
     */
    public function assignList()
    {
        Document::factory()->count(10)->create();
        $response = $this->getJson( route('documents.assign.list'));
        $response->assertStatus(200)
            ->assertJsonPath(
                "data",
                $response->json()['data']
            );
    }



    /**
     *
     * @test
     */
    public function assign()
    {
        $document = Document::factory()->create();
        $params = [
            "user_id" => User::factory()->create()->id,
            "document_id" => $document->id,
            "role_type" => Arr::random(array_column(Roles::cases(), 'value')),
            'appointment_end_time' => Carbon::now()->addDays(4)->format('Y/m/d')
        ];


        $response = $this->postJson( route('documents.assign'), $params);
        $response->assertStatus(200);
    }

}
