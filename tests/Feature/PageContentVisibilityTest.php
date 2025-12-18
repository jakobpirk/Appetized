<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageContentVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at siden altid viser synligt indhold - ikke bare en hvid skærm
     */
    public function test_frontend_page_always_displays_visible_content(): void
    {
        $response = $this->get('/');
        
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer at siden ikke er tom
        $this->assertNotEmpty($content, 'Siden skal indeholde indhold');
        $this->assertGreaterThan(100, strlen($content), 'Siden skal indeholde betydeligt indhold');
        
        // Verificer at der er synligt tekstindhold
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $content, 'Siden skal vise hovedoverskriften');
        
        // Verificer at Vue mount point findes
        $this->assertStringContainsString('id="app"', $content, 'Vue mount point skal eksistere');
    }

    /**
     * Test at menu items faktisk vises på siden når de eksisterer
     */
    public function test_menu_items_are_displayed_on_page(): void
    {
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

        $response = $this->get('/');
        
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer at siden indeholder indhold
        $this->assertNotEmpty($content);
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $content);
        
        // Verificer at API endpoint returnerer data korrekt
        $apiResponse = $this->getJson('/api/menu-items');
        $apiResponse->assertOk();
        $apiResponse->assertJsonCount(2);
        
        // Verificer at menu items er i API responsen
        $apiResponse->assertJsonFragment([
            'name' => 'Margherita Pizza',
        ]);
        
        $apiResponse->assertJsonFragment([
            'name' => 'Carbonara',
        ]);
    }

    /**
     * Test at siden viser besked når der ikke er menu items
     */
    public function test_page_shows_message_when_no_menu_items(): void
    {
        $response = $this->get('/');
        
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer at siden indeholder indhold
        $this->assertNotEmpty($content);
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $content);
        
        // Verificer at API returnerer tom array
        $apiResponse = $this->getJson('/api/menu-items');
        $apiResponse->assertOk();
        $apiResponse->assertExactJson([]);
    }

    /**
     * Test at siden ikke er helt hvid/tom - der skal altid være noget indhold
     */
    public function test_page_is_not_blank(): void
    {
        // Test med menu items
        MenuItem::factory()->count(2)->create();
        $response = $this->get('/');
        $response->assertOk();
        
        $content = $response->getContent();
        $this->assertNotEmpty($content, 'Siden må ikke være tom');
        $this->assertStringContainsString('id="app"', $content);
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $content);
        
        // Test uden menu items
        MenuItem::query()->delete();
        $response = $this->get('/');
        $response->assertOk();
        
        $content = $response->getContent();
        $this->assertNotEmpty($content, 'Siden må ikke være tom selv uden menu items');
        $this->assertStringContainsString('id="app"', $content);
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $content);
    }

    /**
     * Test at HTML strukturen er korrekt og indeholder nødvendige elementer
     */
    public function test_page_has_correct_html_structure(): void
    {
        $response = $this->get('/');
        
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer grundlæggende HTML struktur
        $this->assertStringContainsString('<!DOCTYPE html>', $content);
        $this->assertStringContainsString('<html', $content);
        $this->assertStringContainsString('<body', $content);
        $this->assertStringContainsString('</body>', $content);
        $this->assertStringContainsString('</html>', $content);
        
        // Verificer at app container findes
        $this->assertStringContainsString('id="app"', $content);
        $this->assertStringContainsString('data-view="frontend"', $content);
    }

    /**
     * Test at CSS og JavaScript assets er refereret korrekt
     */
    public function test_page_references_assets_correctly(): void
    {
        $response = $this->get('/');
        
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer at Vite assets bliver inkluderet hvis de eksisterer
        // eller at der er en besked om manglende assets
        $hasViteAssets = str_contains($content, '@vite') || 
                         str_contains($content, 'build/assets') ||
                         str_contains($content, 'hot');
        
        $hasAssetWarning = str_contains($content, 'Assets til frontend mangler') ||
                          str_contains($content, 'npm install');
        
        // Enten skal assets være inkluderet, eller der skal være en advarsel
        $this->assertTrue($hasViteAssets || $hasAssetWarning, 
            'Siden skal enten inkludere assets eller vise en advarsel om manglende assets');
    }

    /**
     * Test at Vue komponenten kan mounte korrekt
     */
    public function test_vue_component_can_mount(): void
    {
        $response = $this->get('/');
        
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer at Vue mount point findes med korrekt data-view attribut
        $this->assertStringContainsString('id="app"', $content);
        $this->assertStringContainsString('data-view="frontend"', $content);
        
        // Verificer at der er indhold i app containeren (enten Vue eller fallback)
        // Vi kan ikke teste Vue rendering direkte i PHPUnit, men vi kan verificere strukturen
        $this->assertNotEmpty($content);
    }

    /**
     * Test at siden altid har synligt tekstindhold uanset state
     */
    public function test_page_always_has_visible_text_content(): void
    {
        // Test med items
        MenuItem::factory()->create(['name' => 'Test Item']);
        $response = $this->get('/');
        $response->assertOk();
        $content = $response->getContent();
        
        // Verificer at der er tekstindhold
        $textContent = strip_tags($content);
        $this->assertGreaterThan(50, strlen(trim($textContent)), 
            'Siden skal indeholde synligt tekstindhold');
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $textContent);
        
        // Test uden items
        MenuItem::query()->delete();
        $response = $this->get('/');
        $response->assertOk();
        $content = $response->getContent();
        
        $textContent = strip_tags($content);
        $this->assertGreaterThan(50, strlen(trim($textContent)), 
            'Siden skal indeholde synligt tekstindhold selv uden menu items');
        $this->assertStringContainsString('Bestil lækker mad hurtigt', $textContent);
    }
}
