<?php

namespace Tests\Feature\APIAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    protected $seed = true;
    use RefreshDatabase;

    public function test_registers_successfully()
    {
        $register_details = [
            'name' => 'Manicure',
            'email' => 'manicure@pedicure.com',
            'password' => 'manicure_pedicure',
            'password_confirmation' => 'manicure_pedicure'
        ];

        $this->postJson('api/register', $register_details)
                ->assertStatus(201)
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

    public function test_requires_password_email_and_name()
    {
        $this->postJson('api/register')
                ->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'password', 'email']);
    }

    public function test_requires_password_confirmation()
    {
        $register_details = [
            'name' => 'Manicure',
            'email' => 'manicure@pedicure.com',
            'password' => 'manicure_pedicure',
        ];

        $this->postJson('api/register', $register_details)
                ->assertStatus(422)
                ->assertJsonValidationErrors('password');
    }
}
