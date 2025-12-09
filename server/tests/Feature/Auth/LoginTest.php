<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_valid_credentials(): void
    {
        User::factory()->create(['email' => 'john@example.com', 'password' => 'password123']);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'user']);
    }

    #[Test]
    public function login_requires_valid_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function password_must_match_database()
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'different123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
