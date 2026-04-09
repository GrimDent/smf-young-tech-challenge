<?php

namespace Tests\Feature;

use App\Models\Contractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractorCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_contractors()
    {
        Contractor::factory()->count(3)->create();

        $response = $this->getJson('/api/contractors');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_contractor()
    {
        $data = [
            'name' => 'Firma Testowa',
            'tax_id' => '1234567890',
            'address' => 'ul. Wiejska 1, Warszawa',
        ];

        $response = $this->postJson('/api/contractors', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contractors', ['tax_id' => '1234567890']);
    }

    public function test_can_show_contractor()
    {
        $contractor = Contractor::factory()->create();

        $response = $this->getJson("/api/contractors/{$contractor->id}");

        $response->assertStatus(200)
            ->assertJsonPath('name', $contractor->name);
    }
}
