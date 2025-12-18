<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_page_displays_form_and_list(): void
    {
        $response = $this->get('/admin');

        $response->assertOk();
        $response->assertSee('Administrer menuen i realtid', false);
        $response->assertSee('data-menu-form', false);
        $response->assertSee('data-menu-list', false);
    }
}
