<?php

namespace Tests\Feature\Auth;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\CookieManager;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_with_valid_credentials_returns_200_and_cookies(): void
    {
        $user = User::factory()->active()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'success', 'message'])
            ->assertJson(['success' => true])
            ->assertCookie(CookieManager::accessTokenCookieName())
            ->assertCookie(CookieManager::refreshTokenCookieName());
    }

    public function test_login_with_invalid_email_returns_401(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Credenciais inválidas',
            ]);
    }

    public function test_login_with_wrong_password_returns_401(): void
    {
        User::factory()->active()->create([
            'email' => 'test@example.com',
            'password' => 'correct-password',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Credenciais inválidas',
            ]);
    }

    public function test_login_with_missing_fields_returns_error(): void
    {
        $response = $this->postJson('/api/v1/login', []);

        // Validation exception is caught by the controller's try-catch
        $response->assertJson(['success' => false]);
    }

    public function test_login_with_missing_password_returns_error(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertJson(['success' => false]);
    }

    public function test_logout_with_authenticated_user_returns_200(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout realizado com sucesso',
            ]);
    }

    public function test_logout_without_authentication_returns_401(): void
    {
        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(401);
    }

    public function test_successful_login_creates_audit_log(): void
    {
        $user = User::factory()->active()->create([
            'email' => 'audit@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'audit@example.com',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => AuditLog::ACTION_LOGIN,
        ]);
    }

    public function test_failed_login_creates_audit_log(): void
    {
        $this->postJson('/api/v1/login', [
            'email' => 'fail@example.com',
            'password' => 'wrong',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => AuditLog::ACTION_LOGIN_FAILED,
        ]);
    }
}
