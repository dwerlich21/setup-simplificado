<?php

namespace Tests\Feature\Audit;

use App\Models\AuditLog;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    public function test_list_audit_logs_returns_200(): void
    {
        $user = $this->actingAsAdmin();

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => AuditLog::ACTION_LOGIN,
            'description' => 'Test login',
        ]);

        $response = $this->getJson('/api/v1/audit-logs');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data',
                    'count',
                    'pages',
                    'page',
                    'per_page',
                ],
            ]);
    }

    public function test_show_audit_log_details(): void
    {
        $user = $this->actingAsAdmin();

        $log = AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => AuditLog::ACTION_CREATED,
            'model_type' => 'App\\Models\\User',
            'model_id' => $user->id,
            'description' => 'User created',
        ]);

        $response = $this->getJson("/api/v1/audit-logs/{$log->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $log->id,
                    'action' => AuditLog::ACTION_CREATED,
                ],
            ]);
    }

    public function test_list_available_actions(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/v1/audit-logs/actions');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['value', 'label'],
                ],
            ]);
    }

    public function test_list_model_types(): void
    {
        $user = $this->actingAsAdmin();

        // Create an audit log so there's at least one model type
        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => AuditLog::ACTION_CREATED,
            'model_type' => 'App\\Models\\User',
            'model_id' => 1,
            'description' => 'Test',
        ]);

        $response = $this->getJson('/api/v1/audit-logs/model-types');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
