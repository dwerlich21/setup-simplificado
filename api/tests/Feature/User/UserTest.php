<?php

namespace Tests\Feature\User;

use App\Models\Permission;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_list_users_with_permission_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_list_users_without_authentication_returns_401(): void
    {
        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(401);
    }

    public function test_list_users_without_permission_returns_403(): void
    {
        // Authenticate without any permissions
        $this->actingAsUser();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_create_user_with_valid_data_returns_201(): void
    {
        $this->actingAsAdmin();

        $permission = Permission::firstOrCreate(
            ['name' => 'test.permission'],
            ['slug' => 'test.permission', 'description' => 'Test']
        );

        $response = $this->postJson('/api/v1/users', [
            'basicInfo' => [
                'name' => 'Novo Usuário',
                'email' => 'novo@example.com',
                'cpf' => '529.982.247-25', // Valid CPF
                'type' => 'user',
                'position' => 'Desenvolvedor',
                'phone' => '11999887766',
                'password' => 'senha12345',
            ],
            'address' => [
                'zipCode' => '01001-000',
                'uf' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Centro',
                'address' => 'Rua Teste',
                'number' => '100',
            ],
            'permissions' => [$permission->id],
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'novo@example.com',
            'name' => 'Novo Usuário',
        ]);
    }

    public function test_create_user_with_duplicate_email_returns_422(): void
    {
        $this->actingAsAdmin();

        User::factory()->create(['email' => 'duplicate@example.com']);

        $permission = Permission::firstOrCreate(
            ['name' => 'test.permission'],
            ['slug' => 'test.permission', 'description' => 'Test']
        );

        $response = $this->postJson('/api/v1/users', [
            'basicInfo' => [
                'name' => 'Outro Usuário',
                'email' => 'duplicate@example.com',
                'cpf' => '529.982.247-25',
                'type' => 'user',
                'position' => 'Desenvolvedor',
                'phone' => '11999887766',
            ],
            'address' => [
                'zipCode' => '01001-000',
                'uf' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Centro',
                'address' => 'Rua Teste',
            ],
            'permissions' => [$permission->id],
        ]);

        $response->assertStatus(422);
    }

    public function test_create_user_with_invalid_cpf_returns_422(): void
    {
        $this->actingAsAdmin();

        $permission = Permission::firstOrCreate(
            ['name' => 'test.permission'],
            ['slug' => 'test.permission', 'description' => 'Test']
        );

        $response = $this->postJson('/api/v1/users', [
            'basicInfo' => [
                'name' => 'Usuário CPF Inválido',
                'email' => 'cpfinvalid@example.com',
                'cpf' => '111.111.111-11', // Invalid CPF (repeated digits)
                'type' => 'user',
                'position' => 'Desenvolvedor',
                'phone' => '11999887766',
            ],
            'address' => [
                'zipCode' => '01001-000',
                'uf' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Centro',
                'address' => 'Rua Teste',
            ],
            'permissions' => [$permission->id],
        ]);

        $response->assertStatus(422);
    }

    public function test_update_user_returns_200(): void
    {
        $admin = $this->actingAsAdmin();

        $user = User::factory()->create();

        $permission = Permission::firstOrCreate(
            ['name' => 'test.permission'],
            ['slug' => 'test.permission', 'description' => 'Test']
        );

        $encodedId = base64_encode($user->id);

        $response = $this->putJson("/api/v1/users/{$encodedId}", [
            'basicInfo' => [
                'name' => 'Nome Atualizado',
                'email' => $user->email,
                'cpf' => $user->cpf,
                'type' => $user->type,
                'position' => $user->position,
                'phone' => $user->phone,
            ],
            'address' => [
                'zipCode' => '01001-000',
                'uf' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Centro',
                'address' => 'Rua Teste',
            ],
            'permissions' => [$permission->id],
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_delete_user_returns_204(): void
    {
        $this->actingAsAdmin();

        $user = User::factory()->create();
        $encodedId = base64_encode($user->id);

        $response = $this->deleteJson("/api/v1/users/{$encodedId}");

        $response->assertStatus(204);

        // Soft delete - still exists in DB but has deleted_at
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_bulk_delete_users_returns_200(): void
    {
        $this->actingAsAdmin();

        $users = User::factory()->count(3)->create();
        $ids = $users->pluck('id')->toArray();

        $response = $this->postJson('/api/v1/users/bulk-delete', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_change_active_returns_200(): void
    {
        $this->actingAsAdmin();

        $user = User::factory()->active()->create();
        $encodedId = base64_encode($user->id);

        $response = $this->putJson("/api/v1/users/change-active/{$encodedId}");

        $response->assertStatus(204);

        $user->refresh();
        $this->assertFalse($user->active);
    }
}
