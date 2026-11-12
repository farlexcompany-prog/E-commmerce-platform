<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_the_pharmacy_storefront(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Farlex Pharmaceuticals')
            ->assertSee('images/farlex-logo.png')
            ->assertSee('https://www.linkedin.com/company/farlex-pharmaceuticals-pvt-ltd/')
            ->assertSee('https://www.instagram.com/farlex.pharma/')
            ->assertSee('https://www.pinterest.com/farlexpharmaceuticals/')
            ->assertSee('https://twitter.com/farlexpharma')
            ->assertSee('Care for every day.')
            ->assertSee('Sample OTC Tablet Pack')
            ->assertSee('Medicines should be taken only as directed by a registered medical practitioner.');
    }
}
