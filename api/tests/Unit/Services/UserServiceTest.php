<?php

namespace Tests\Unit\Services;

use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UserService::class);
    }

    public function test_create_user_saves_address_correctly(): void
    {
        $admin = $this->actingAsAdmin();

        $permission = Permission::firstOrCreate(
            ['name' => 'test.perm'],
            ['slug' => 'test.perm', 'description' => 'Test']
        );

        $data = [
            'basicInfo' => [
                'name' => 'Teste Endereço',
                'email' => 'address@test.com',
                'cpf' => '52998224725',
                'type' => 'user',
                'position' => 'Dev',
                'phone' => '11999887766',
                'password' => 'password123',
            ],
            'address' => [
                'zipCode' => '01001-000',
                'uf' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Centro',
                'address' => 'Rua das Flores',
                'number' => '42',
                'complement' => 'Sala 1',
            ],
            'permissions' => [$permission->id],
        ];

        $user = $this->service->create($data);

        $this->assertNotNull($user->address);
        $this->assertEquals('SP', $user->address->uf);
        $this->assertEquals('São Paulo', $user->address->city);
        $this->assertEquals('Rua das Flores', $user->address->address);
    }

    public function test_create_user_saves_permissions(): void
    {
        $admin = $this->actingAsAdmin();

        $perm1 = Permission::firstOrCreate(
            ['name' => 'perm.one'],
            ['slug' => 'perm.one', 'description' => 'Perm 1']
        );
        $perm2 = Permission::firstOrCreate(
            ['name' => 'perm.two'],
            ['slug' => 'perm.two', 'description' => 'Perm 2']
        );

        $data = [
            'basicInfo' => [
                'name' => 'User Perms',
                'email' => 'perms@test.com',
                'cpf' => '52998224725',
                'type' => 'user',
                'position' => 'Dev',
                'phone' => '11999887766',
            ],
            'address' => [
                'zipCode' => '01001-000',
                'uf' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Centro',
                'address' => 'Rua Teste',
            ],
            'permissions' => [$perm1->id, $perm2->id],
        ];

        $user = $this->service->create($data);

        $this->assertCount(2, $user->permissions);
        $permNames = $user->permissions->pluck('name')->toArray();
        $this->assertContains('perm.one', $permNames);
        $this->assertContains('perm.two', $permNames);
    }

    public function test_cpf_rule_validates_correct_cpf(): void
    {
        $rule = new \App\Rules\Cpf();
        $failed = false;

        $rule->validate('cpf', '52998224725', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed, 'Valid CPF should not fail validation');
    }

    public function test_cpf_rule_rejects_invalid_cpf(): void
    {
        $rule = new \App\Rules\Cpf();
        $failed = false;

        // All repeated digits should fail
        $rule->validate('cpf', '11111111111', function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, 'Invalid CPF (repeated digits) should fail validation');

        // Wrong check digits
        $failed = false;
        $rule->validate('cpf', '12345678901', function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, 'Invalid CPF (wrong check digits) should fail validation');
    }
}
