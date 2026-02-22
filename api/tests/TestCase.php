<?php

namespace Tests;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Create a user with given permissions and authenticate via Sanctum.
     */
    protected function actingAsUser(array $permissions = [], array $userAttributes = []): User
    {
        $user = User::factory()->active()->create($userAttributes);

        if (!empty($permissions)) {
            $permissionIds = [];
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['slug' => $permissionName, 'description' => $permissionName]
                );
                $permissionIds[] = $permission->id;
            }
            $user->permissions()->sync($permissionIds);
        }

        Sanctum::actingAs($user);

        return $user;
    }

    /**
     * Create an admin user with all common permissions and authenticate.
     */
    protected function actingAsAdmin(array $extraAttributes = []): User
    {
        $permissions = [
            'users.index', 'users.store', 'users.show', 'users.update', 'users.destroy',
            'users.change-active', 'users.bulk-delete', 'users.bulk-change-active',
            'goals.index', 'goals.store', 'goals.show', 'goals.update', 'goals.destroy',
            'goals.change-active', 'goals.bulk-delete', 'goals.bulk-change-active',
            'notifications.index', 'notifications.unread-count', 'notifications.mark-read',
            'notifications.mark-all-read', 'notifications.bulk-delete', 'notifications.destroy',
            'audit-logs.index', 'audit-logs.show', 'audit-logs.stats',
            'reports.dashboard', 'reports.goals', 'reports.users',
            'reports.export-pdf', 'reports.export-excel',
        ];

        return $this->actingAsUser(
            $permissions,
            array_merge(['type' => 'admin'], $extraAttributes)
        );
    }
}
