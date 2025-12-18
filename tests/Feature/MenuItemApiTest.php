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
        $items = MenuItem::factory()->count(2)->create();

        $response = $this->getJson('/api/menu-items');

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['name' => $items->first()->name]);
    }

    public function test_it_creates_a_new_menu_item(): void
    {
        $payload = [
            'name' => 'Testret',
            'description' => 'Frisk og lækker',
            'price' => 129.50,
            'available' => true,
        ];

        $response = $this->postJson('/api/menu-items', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('menu_items', [
            'name' => 'Testret',
            'available' => true,
        ]);
    }

    public function test_validation_errors_are_returned(): void
    {
        $response = $this->postJson('/api/menu-items', [
            'name' => '',
            'price' => -1,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name', 'price']);
    }
}
