<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendContentDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_page_displays_menu_items_when_available(): void
    {
        // Opret menu items
        $item1 = MenuItem::factory()->create([
            'name' => 'Margherita Pizza',
            'description' => 'Klassisk pizza med mozzarella og basilikum',
            'price' => 95.00,
            'available' => true,
        ]);

        $item2 = MenuItem::factory()->create([
            'name' => 'Carbonara',
            'description' => 'Cremet pasta med bacon og æg',
            'price' => 125.00,
            'available' => true,
        ]);

        // Hent forsiden
        $response = $this->get('/');

        $response->assertOk();
        
        // Verificer at API endpoint returnerer menu items
        $apiResponse = $this->getJson('/api/menu-items');
        $apiResponse->assertOk();
        $apiResponse->assertJsonCount(2);
        
        // Verificer at menu items er i API responsen
        $apiResponse->assertJsonFragment([
            'name' => 'Margherita Pizza',
            'description' => 'Klassisk pizza med mozzarella og basilikum',
        ]);
        
        $apiResponse->assertJsonFragment([
            'name' => 'Carbonara',
            'description' => 'Cremet pasta med bacon og æg',
        ]);
    }

    public function test_frontend_page_shows_message_when_no_menu_items(): void
    {
        // Ingen menu items oprettet
        
        $response = $this->get('/');
        
        $response->assertOk();
        
        // Verificer at siden indeholder noget indhold
        $response->assertSee('Bestil lækker mad hurtigt');
        $response->assertSee('id="app"', false);
        
        // Verificer at API returnerer tom array
        $apiResponse = $this->getJson('/api/menu-items');
        $apiResponse->assertOk();
        $apiResponse->assertJsonCount(0);
        $apiResponse->assertExactJson([]);
    }

    public function test_frontend_page_displays_correct_structure(): void
    {
        MenuItem::factory()->count(3)->create();
        
        $response = $this->get('/');
        
        $response->assertOk();
        
        // Verificer at Vue app container findes
        $response->assertSee('id="app"', false);
        $response->assertSee('data-view="frontend"', false);
        
        // Verificer at statisk fallback tekst findes
        $response->assertSee('Bestil lækker mad hurtigt');
        $response->assertSee('Frontend indlæses');
    }

    public function test_menu_items_are_ordered_correctly(): void
    {
        // Opret items med forskellige available status
        $unavailable = MenuItem::factory()->create([
            'name' => 'Unavailable Item',
            'available' => false,
        ]);
        
        $available1 = MenuItem::factory()->create([
            'name' => 'Available Item A',
            'available' => true,
        ]);
        
        $available2 = MenuItem::factory()->create([
            'name' => 'Available Item B',
            'available' => true,
        ]);
        
        $apiResponse = $this->getJson('/api/menu-items');
        
        $apiResponse->assertOk();
        $items = $apiResponse->json();
        
        // Verificer at available items kommer først
        $this->assertTrue($items[0]['available']);
        $this->assertTrue($items[1]['available']);
        $this->assertFalse($items[2]['available']);
        
        // Verificer at available items er sorteret alfabetisk
        $this->assertEquals('Available Item A', $items[0]['name']);
        $this->assertEquals('Available Item B', $items[1]['name']);
    }

    public function test_frontend_api_endpoint_returns_valid_json_structure(): void
    {
        $item = MenuItem::factory()->create([
            'name' => 'Test Item',
            'description' => 'Test Description',
            'price' => 100.50,
            'available' => true,
        ]);
        
        $response = $this->getJson('/api/menu-items');
        
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'price',
                'available',
                'created_at',
                'updated_at',
            ],
        ]);
        
        $response->assertJsonPath('0.name', 'Test Item');
        $response->assertJsonPath('0.price', '100.50');
        $response->assertJsonPath('0.available', true);
    }

    public function test_api_endpoint_is_accessible_from_frontend(): void
    {
        // Verificer at API endpoint kan tilgås uden autentificering
        $response = $this->get('/api/menu-items');
        
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_frontend_page_contains_vue_mount_point(): void
    {
        $response = $this->get('/');
        
        $response->assertOk();
        
        // Verificer at Vue mount point findes
        $content = $response->getContent();
        $this->assertStringContainsString('id="app"', $content);
        $this->assertStringContainsString('data-view="frontend"', $content);
    }

    public function test_api_returns_empty_array_when_no_items(): void
    {
        $response = $this->getJson('/api/menu-items');
        
        $response->assertOk();
        $response->assertExactJson([]);
    }

    public function test_menu_items_have_correct_data_types(): void
    {
        $item = MenuItem::factory()->create([
            'name' => 'Test Pizza',
            'description' => 'Test Description',
            'price' => 99.99,
            'available' => true,
        ]);
        
        $response = $this->getJson('/api/menu-items');
        
        $response->assertOk();
        $data = $response->json();
        
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertIsInt($data[0]['id']);
        $this->assertIsString($data[0]['name']);
        $this->assertIsString($data[0]['description']);
        $this->assertIsString($data[0]['price']); // Decimal returns as string in JSON
        $this->assertIsBool($data[0]['available']);
    }

    public function test_frontend_page_always_shows_content(): void
    {
        // Test med menu items
        MenuItem::factory()->count(2)->create();
        $response = $this->get('/');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertNotEmpty($content);
        $this->assertStringContainsString('id="app"', $content);
        
        // Test uden menu items
        MenuItem::query()->delete();
        $response = $this->get('/');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertNotEmpty($content);
        $this->assertStringContainsString('id="app"', $content);
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $content);
    }

    public function test_api_route_works_without_authentication(): void
    {
        // Verificer at API route ikke kræver autentificering
        MenuItem::factory()->create(['name' => 'Public Item']);
        
        $response = $this->getJson('/api/menu-items');
        
        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Public Item']);
    }

    public function test_api_response_is_valid_json_array(): void
    {
        MenuItem::factory()->count(3)->create();
        
        $response = $this->get('/api/menu-items');
        
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');
        
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(3, $data);
        
        // Verificer at hvert element har de nødvendige felter
        foreach ($data as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('available', $item);
        }
    }

    public function test_frontend_displays_multiple_menu_items(): void
    {
        // Opret flere menu items med forskellige priser og status
        MenuItem::factory()->create([
            'name' => 'Pizza Margherita',
            'price' => 95.00,
            'available' => true,
        ]);
        
        MenuItem::factory()->create([
            'name' => 'Pasta Carbonara',
            'price' => 125.00,
            'available' => true,
        ]);
        
        MenuItem::factory()->create([
            'name' => 'Dessert',
            'price' => 45.00,
            'available' => false,
        ]);
        
        $apiResponse = $this->getJson('/api/menu-items');
        
        $apiResponse->assertOk();
        $apiResponse->assertJsonCount(3);
        
        // Verificer at available items kommer først (sorteret efter available desc, derefter name asc)
        $items = $apiResponse->json();
        $this->assertTrue($items[0]['available']);
        $this->assertTrue($items[1]['available']);
        $this->assertFalse($items[2]['available']);
    }
}
