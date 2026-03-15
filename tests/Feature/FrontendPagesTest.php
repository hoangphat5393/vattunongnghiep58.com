<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    public function test_home_page_is_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_list_page_is_accessible()
    {
        $response = $this->get(route('product'));

        $response->assertStatus(200);
    }

    public function test_news_list_page_is_accessible()
    {
        $response = $this->get(route('news'));

        $response->assertStatus(200);
    }

    public function test_contact_page_is_accessible()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }
}
