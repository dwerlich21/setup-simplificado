<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function test_forgot_password_with_existing_email_returns_200(): void
    {
        User::factory()->active()->create([
            'email' => 'forgot@example.com',
        ]);

        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => 'forgot@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_forgot_password_with_nonexistent_email_returns_error(): void
    {
        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        // NotFoundException is thrown which results in 404
        $response->assertStatus(404);
    }

    public function test_recover_password_with_valid_token_returns_200(): void
    {
        $user = User::factory()->active()->create([
            'email' => 'recover@example.com',
        ]);

        // Create a valid password reset token
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/v1/recover-password', [
            'email' => 'recover@example.com',
            'token' => $token,
            'password' => 'newpassword123',
            'password2' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify password was actually changed
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_recover_password_with_invalid_token_returns_error(): void
    {
        User::factory()->active()->create([
            'email' => 'recover@example.com',
        ]);

        $response = $this->postJson('/api/v1/recover-password', [
            'email' => 'recover@example.com',
            'token' => 'invalid-token',
            'password' => 'newpassword123',
            'password2' => 'newpassword123',
        ]);

        $response->assertStatus(404);
    }
}
