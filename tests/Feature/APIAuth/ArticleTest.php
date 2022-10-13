<?php

namespace Tests\Feature\ApiAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;

class ArticleTest extends TestCase
{
    // protected $seed = true;
    use RefreshDatabase;
    
    public function test_articles_are_created_properly()
    {
        $user = User::factory()->create();
        $token = $user->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $data = [
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ];

        $this->postJson('/api/articles', $data, $headers)
            ->assertStatus(201)
            ->assertJson(['id' => 1, 'title' => 'Lorem', 'body' => 'Ipsum']);
    }

    public function test_articles_are_updated_properply()
    {
        $user = User::factory()->create();
        $token = $user->generateToken();
        
        $headers = ['Authorization' => "Bearer $token"];

        $article = Article::factory()->create([
            'title' => 'First Article',
            'body' => 'First Body'
        ]);

        $data = [
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ];

        $this->putJson('api/articles/' . $article->id, $data, $headers)
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'title' => 'Lorem',
                'body' => 'Ipsum'
            ]);
    }

    public function test_articles_are_deleted_properly()
    {
        $user = User::factory()->create();

        $token = $user->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $article = Article::factory()->create([
            'title' => 'First Article',
            'body' => 'First Body'
        ]);

        $this->deleteJson('api/articles/' . $article->id, [], $headers)
            ->assertStatus(204);
    }

    public function test_articles_are_listed_properly()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();

        $token = $user->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        Article::factory()->create([
            'title' => 'First Article',
            'body' => 'First Body'
        ]);

        Article::factory()->create([
            'title' => 'Second Article',
            'body' => 'Second Body'
        ]);

        $this->getJson('api/articles', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                ['title' => 'First Article', 'body' => 'Second Article'],
                ['title' => 'Second Article', 'body' => 'Second Body']
            ])
            ->assertJsonStructure([
                '*' => ['id', 'body', 'title', 'created_at', 'updated_at']
            ]);
    }
}
