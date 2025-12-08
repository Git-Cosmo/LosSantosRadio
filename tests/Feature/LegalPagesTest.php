<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_terms_page_loads_successfully(): void
    {
        $response = $this->get(route('legal.terms'));

        $response->assertStatus(200);
        $response->assertSee('Terms of Service');
        $response->assertSee('Acceptance of Terms');
    }

    public function test_privacy_page_loads_successfully(): void
    {
        $response = $this->get(route('legal.privacy'));

        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
        $response->assertSee('Information We Collect');
    }

    public function test_cookies_page_loads_successfully(): void
    {
        $response = $this->get(route('legal.cookies'));

        $response->assertStatus(200);
        $response->assertSee('Cookie Policy');
    }

    public function test_legal_pages_have_proper_meta_tags(): void
    {
        $response = $this->get(route('legal.terms'));
        $response->assertSee('Los Santos Radio Terms of Service', false);

        $response = $this->get(route('legal.privacy'));
        $response->assertSee('Los Santos Radio Privacy Policy', false);

        $response = $this->get(route('legal.cookies'));
        $response->assertSee('Los Santos Radio Cookie Policy', false);
    }

    public function test_footer_contains_legal_links(): void
    {
        $response = $this->get('/');

        $response->assertSee(route('legal.terms'), false);
        $response->assertSee(route('legal.privacy'), false);
        $response->assertSee(route('legal.cookies'), false);
    }
}
