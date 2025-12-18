<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_with_menu_placeholders(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Byg din menu på få minutter', false);
        $response->assertSee('data-menu-list', false);
        $response->assertSee('data-menu-grid', false);
    }
}
