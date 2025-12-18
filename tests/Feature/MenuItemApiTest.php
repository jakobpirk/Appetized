<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_menu_items(): void
    {
        MenuItem::factory()->count(3)->create();

        $response = $this->getJson('/api/menu-items');

        $response->assertOk()->assertJsonCount(3);
    }

    public function test_it_creates_menu_items(): void
    {
        $payload = [
            'name' => 'Margherita',
            'description' => 'Klassisk med frisk basilikum',
            'price' => 95,
        ];

        $response = $this->postJson('/api/menu-items', $payload);

        $response->assertCreated()->assertJsonPath('name', 'Margherita');
        $this->assertDatabaseHas('menu_items', [
            'name' => 'Margherita',
            'available' => true,
        ]);
    }

    public function test_it_validates_payload(): void
    {
        $response = $this->postJson('/api/menu-items', [
            'name' => '',
            'price' => -10,
        ]);

        $response->assertStatus(422);
    }

    public function test_it_updates_menu_items(): void
    {
        $item = MenuItem::factory()->create([
            'available' => true,
            'price' => 120,
        ]);

        $response = $this->putJson("/api/menu-items/{$item->id}", [
            'name' => 'Opdateret',
            'description' => 'Ny tekst',
            'price' => 110,
            'available' => false,
        ]);

        $response->assertOk()->assertJsonPath('available', false);
        $this->assertDatabaseHas('menu_items', [
            'id' => $item->id,
            'name' => 'Opdateret',
            'available' => false,
        ]);
    }

    public function test_it_deletes_menu_items(): void
    {
        $item = MenuItem::factory()->create();

        $response = $this->deleteJson("/api/menu-items/{$item->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
    }
}
