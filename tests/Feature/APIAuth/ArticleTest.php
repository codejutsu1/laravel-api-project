<?php

namespace Tests\Feature\ApiAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    protected $seed = true;
    use RefreshDatabase;
    
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
