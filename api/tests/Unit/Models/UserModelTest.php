<?php

namespace Tests\Unit\Models;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserAddress;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_user_has_address_relationship(): void
    {
        $user = User::factory()->create();

        UserAddress::create([
            'user_id' => $user->id,
            'zip_code' => '01001-000',
            'uf' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'address' => 'Rua Teste',
        ]);

        $this->assertNotNull($user->address);
        $this->assertInstanceOf(UserAddress::class, $user->address);
        $this->assertEquals('SP', $user->address->uf);
    }

    public function test_user_has_permissions_relationship(): void
    {
        $user = User::factory()->create();

        $permission = Permission::create([
            'name' => 'test.permission',
            'slug' => 'test.permission',
            'description' => 'Test Permission',
        ]);

        $user->permissions()->attach($permission->id);

        $this->assertCount(1, $user->permissions);
        $this->assertEquals('test.permission', $user->permissions->first()->name);
    }

    public function test_auditable_trait_logs_user_creation(): void
    {
        $this->actingAsUser();

        $user = User::factory()->create([
            'name' => 'Audit User',
            'email' => 'audituser@test.com',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => AuditLog::ACTION_CREATED,
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);
    }
}
