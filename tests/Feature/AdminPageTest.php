<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminPageTest extends TestCase
{
    public function test_admin_page_loads(): void
    {
        $response = $this->get('/admin');

        $response->assertOk();
        $response->assertSee('data-view="admin"', false);
    }
}
