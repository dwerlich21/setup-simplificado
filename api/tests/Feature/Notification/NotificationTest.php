<?php

namespace Tests\Feature\Notification;

use App\Models\Notification;
use App\Models\User;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_list_notifications_for_authenticated_user(): void
    {
        $user = $this->actingAsAdmin();

        Notification::create([
            'user_id' => $user->id,
            'type' => Notification::TYPE_GOAL_ASSIGNED,
            'title' => 'Test Notification',
            'message' => 'Test message',
            'read' => false,
        ]);

        $response = $this->getJson('/api/v1/notifications');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_unread_count_returns_correct_number(): void
    {
        $user = $this->actingAsAdmin();

        // Create 3 unread notifications
        for ($i = 0; $i < 3; $i++) {
            Notification::create([
                'user_id' => $user->id,
                'type' => Notification::TYPE_GOAL_ASSIGNED,
                'title' => "Notification {$i}",
                'message' => 'Test message',
                'read' => false,
            ]);
        }

        // Create 1 read notification
        Notification::create([
            'user_id' => $user->id,
            'type' => Notification::TYPE_GOAL_COMPLETED,
            'title' => 'Read Notification',
            'message' => 'Test message',
            'read' => true,
        ]);

        $response = $this->getJson('/api/v1/notifications/unread-count');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['count' => 3],
            ]);
    }

    public function test_mark_notification_as_read(): void
    {
        $user = $this->actingAsAdmin();

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => Notification::TYPE_GOAL_ASSIGNED,
            'title' => 'Unread Notification',
            'message' => 'Test message',
            'read' => false,
        ]);

        $response = $this->putJson("/api/v1/notifications/{$notification->id}/read");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $notification->refresh();
        $this->assertTrue($notification->read);
    }

    public function test_mark_all_notifications_as_read(): void
    {
        $user = $this->actingAsAdmin();

        for ($i = 0; $i < 3; $i++) {
            Notification::create([
                'user_id' => $user->id,
                'type' => Notification::TYPE_GOAL_ASSIGNED,
                'title' => "Notification {$i}",
                'message' => 'Test message',
                'read' => false,
            ]);
        }

        $response = $this->putJson('/api/v1/notifications/mark-all-read');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();

        $this->assertEquals(0, $unreadCount);
    }

    public function test_delete_notification(): void
    {
        $user = $this->actingAsAdmin();

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => Notification::TYPE_GOAL_ASSIGNED,
            'title' => 'To Delete',
            'message' => 'Test message',
            'read' => false,
        ]);

        $response = $this->deleteJson("/api/v1/notifications/{$notification->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    public function test_bulk_delete_notifications(): void
    {
        $user = $this->actingAsAdmin();

        $notifications = [];
        for ($i = 0; $i < 3; $i++) {
            $notifications[] = Notification::create([
                'user_id' => $user->id,
                'type' => Notification::TYPE_GOAL_ASSIGNED,
                'title' => "Notification {$i}",
                'message' => 'Test message',
                'read' => false,
            ]);
        }

        $ids = array_map(fn($n) => $n->id, $notifications);

        $response = $this->postJson('/api/v1/notifications/bulk-delete', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
