<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\MasterData;
use App\Models\Inspection;

class InspectionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create user with ID 1
        User::factory()->create(['id' => 1]);
        $this->seed(\Database\Seeders\MasterDataSeeder::class);
    }

    public function test_it_can_get_master_data()
    {
        $response = $this->getJson('/api/master-data');

        $response->assertStatus(200)
                 ->assertJsonStructure(['inspection_type', 'inspection_status']);
    }

    public function test_it_can_create_inspection()
    {
        $data = [
            'type' => 'NEW_ARRIVAL',
            'items' => [
                [
                    'lot_no' => 'LOT-001',
                    'qty_required' => 10,
                    'qty_available' => 5,
                ]
            ]
        ];

        $response = $this->postJson('/api/inspections', $data);

        $response->assertStatus(201)
                 ->assertJsonPath('type', 'NEW_ARRIVAL')
                 ->assertJsonPath('status', 'OPEN');

        $this->assertDatabaseHas('inspections', ['type' => 'NEW_ARRIVAL']);
        $this->assertDatabaseHas('inspection_items', ['lot_no' => 'LOT-001']);
    }

    public function test_it_can_list_inspections()
    {
        Inspection::create(['type' => 'NEW_ARRIVAL', 'status' => 'OPEN', 'user_id' => 1]);
        Inspection::create(['type' => 'MAINTENANCE', 'status' => 'COMPLETED', 'user_id' => 1]);

        $response = $this->getJson('/api/inspections');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_it_can_filter_inspections_by_status()
    {
        Inspection::create(['type' => 'NEW_ARRIVAL', 'status' => 'OPEN', 'user_id' => 1]);
        Inspection::create(['type' => 'MAINTENANCE', 'status' => 'COMPLETED', 'user_id' => 1]);

        $response = $this->getJson('/api/inspections?status=OPEN');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.status', 'OPEN');
    }

    public function test_it_can_update_inspection_status()
    {
        $inspection = Inspection::create(['type' => 'NEW_ARRIVAL', 'status' => 'OPEN', 'user_id' => 1]);

        $response = $this->putJson("/api/inspections/{$inspection->id}", [
            'status' => 'FOR_REVIEW'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'FOR_REVIEW');

        $this->assertDatabaseHas('inspections', ['id' => $inspection->id, 'status' => 'FOR_REVIEW']);
    }

    public function test_it_cannot_edit_content_if_not_open()
    {
        $inspection = Inspection::create(['type' => 'NEW_ARRIVAL', 'status' => 'FOR_REVIEW', 'user_id' => 1]);

        $response = $this->putJson("/api/inspections/{$inspection->id}", [
            'type' => 'MAINTENANCE'
        ]);

        $response->assertStatus(403);
    }

    public function test_it_cannot_transition_to_invalid_status()
    {
        // OPEN to COMPLETED is invalid (skipping FOR_REVIEW)
        $inspection = Inspection::create(['type' => 'NEW_ARRIVAL', 'status' => 'OPEN', 'user_id' => 1]);
        $response = $this->putJson("/api/inspections/{$inspection->id}", [
            'status' => 'COMPLETED'
        ]);
        $response->assertStatus(422)
                 ->assertJsonPath('message', 'Invalid transition from OPEN to COMPLETED');

        // COMPLETED to OPEN is invalid (terminal state)
        $inspectionTerminal = Inspection::create(['type' => 'MAINTENANCE', 'status' => 'COMPLETED', 'user_id' => 1]);
        $responseTerminal = $this->putJson("/api/inspections/{$inspectionTerminal->id}", [
            'status' => 'OPEN'
        ]);
        $responseTerminal->assertStatus(422)
                 ->assertJsonPath('message', 'Invalid transition from COMPLETED to OPEN');
    }
}
