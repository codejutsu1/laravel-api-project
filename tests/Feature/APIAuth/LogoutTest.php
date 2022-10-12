<?php

namespace Tests\Feature\APIAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    protected $seed = true;
    use RefreshDatabase;

    public function test_user_is_logged_out_properly()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'email' =>  'user@test.com'
        ]);

        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer ".$token, "Accept" => 'application/json'];

        $this->getJson('/api/articles', $headers)->assertStatus(200);
        $this->postJson('/api/logout', [], $headers)->assertStatus(200);

        $user = User::find($user->id);

        $this->assertEquals(null, $user->api_token);
    }

    public function test_user_with_null_token()
    {
        $user = User::factory()->create([
            'email' =>  'user@test.com'
        ]);

        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $user->api_token = null;
        $user->save();

        $this->getJson('api/articles', [], $headers)->assertStatus(401);
    }
}
