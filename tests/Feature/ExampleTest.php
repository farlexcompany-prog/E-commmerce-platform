<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_storefront_displays_seeded_products(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Everyday Backpack')
            ->assertSee('$59.00');
    }
}
