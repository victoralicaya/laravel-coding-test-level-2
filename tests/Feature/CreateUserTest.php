<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * Test creating a user via API.
     *
     * @return void
     */
    public function testCreateUserViaApi()
    {
        $userData = [
            'username' => 'admin',
            'password' => $this->faker->password(),
            'role' => 'admin'
        ];

        $response = $this->json('POST', '/api/v1/users', $userData);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'username' => $userData['username'],
                'role' => $userData['role'],
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'username' => $userData['username'],
            'role' => $userData['role']
        ]);
    }
}
