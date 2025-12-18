<?php

namespace Tests\Feature;

use Tests\TestCase;

class ViewRenderingTest extends TestCase
{
    public function test_frontend_view_shows_static_fallback(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Bestil lækker mad hurtigt');
        $response->assertSee('Frontend indlæses');
        $response->assertSee('Menukortet vises automatisk');
    }

    public function test_admin_view_shows_static_fallback(): void
    {
        $response = $this->get('/admin');

        $response->assertOk();
        $response->assertSee('Menu styring');
        $response->assertSee('Adminpanelet initialiseres');
        $response->assertSee('Vite-kørslen er aktiv');
    }

    public function test_view_contains_asset_guidance_when_vite_missing(): void
    {
        $response = $this->get('/');

        $response->assertSee('Assets til frontend mangler');
        $response->assertSee('npm run dev');
    }
}
