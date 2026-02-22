<?php

namespace Tests\Feature\Permission;

use App\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_list_permissions_returns_hierarchical_structure(): void
    {
        $this->actingAsUser();

        // Create parent permission
        $parent = Permission::create([
            'name' => 'users',
            'slug' => 'users',
            'description' => 'Gerenciar Usuários',
        ]);

        // Create child permissions
        Permission::create([
            'name' => 'users.index',
            'slug' => 'users.index',
            'description' => 'Listar Usuários',
            'parent_id' => $parent->id,
        ]);

        Permission::create([
            'name' => 'users.store',
            'slug' => 'users.store',
            'description' => 'Criar Usuários',
            'parent_id' => $parent->id,
        ]);

        $response = $this->getJson('/api/v1/permissions');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'children',
                    ],
                ],
            ]);
    }

    public function test_access_protected_route_without_permission_returns_403(): void
    {
        // Authenticate without any permissions
        $this->actingAsUser();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Você não tem permissão para acessar este recurso.',
            ]);
    }
}
