<?php

namespace Tests\Feature\APIAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    protected $seed = true;
    use RefreshDatabase;

    public function test_requires_email_and_login()
    {
        // $this->withoutExceptionHandling();
        $reponse = $this->postJson('api/login');
                
        $reponse->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_user_logins_successfully()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'email' => 'test@login.com',
            'password' => Hash::make('password')
        ]);

        $login_details = ['email' => 'test@login.com', 'password' => 'password'];

        $this->postJson('api/login', $login_details)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name', 
                    'email',
                    'created_at',
                    'updated_at',
                    'api_token'
                ],
            ]);
    }
}
