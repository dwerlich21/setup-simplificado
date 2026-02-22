<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([
            Notification::TYPE_GOAL_ASSIGNED,
            Notification::TYPE_GOAL_DEADLINE,
            Notification::TYPE_GOAL_COMPLETED,
        ]);

        return [
            'user_id'    => User::factory(),
            'type'       => $type,
            'title'      => $this->titleForType($type),
            'message'    => $this->messageForType($type),
            'data'       => $this->dataForType($type),
            'read'       => fake()->boolean(40),
            'email_sent' => fake()->boolean(70),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the notification has been read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read' => true,
        ]);
    }

    /**
     * Indicate that the notification is unread.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read' => false,
        ]);
    }

    /**
     * Set the notification type to goal_assigned.
     */
    public function goalAssigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'    => Notification::TYPE_GOAL_ASSIGNED,
            'title'   => $this->titleForType(Notification::TYPE_GOAL_ASSIGNED),
            'message' => $this->messageForType(Notification::TYPE_GOAL_ASSIGNED),
            'data'    => $this->dataForType(Notification::TYPE_GOAL_ASSIGNED),
        ]);
    }

    /**
     * Set the notification type to goal_deadline.
     */
    public function goalDeadline(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'    => Notification::TYPE_GOAL_DEADLINE,
            'title'   => $this->titleForType(Notification::TYPE_GOAL_DEADLINE),
            'message' => $this->messageForType(Notification::TYPE_GOAL_DEADLINE),
            'data'    => $this->dataForType(Notification::TYPE_GOAL_DEADLINE),
        ]);
    }

    /**
     * Set the notification type to goal_completed.
     */
    public function goalCompleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'    => Notification::TYPE_GOAL_COMPLETED,
            'title'   => $this->titleForType(Notification::TYPE_GOAL_COMPLETED),
            'message' => $this->messageForType(Notification::TYPE_GOAL_COMPLETED),
            'data'    => $this->dataForType(Notification::TYPE_GOAL_COMPLETED),
        ]);
    }

    private function titleForType(string $type): string
    {
        return match ($type) {
            Notification::TYPE_GOAL_ASSIGNED  => 'Nova meta atribuída',
            Notification::TYPE_GOAL_DEADLINE  => 'Prazo de meta se aproximando',
            Notification::TYPE_GOAL_COMPLETED => 'Meta concluída',
        };
    }

    private function messageForType(string $type): string
    {
        $goalName = fake()->sentence(3, false);

        return match ($type) {
            Notification::TYPE_GOAL_ASSIGNED  => "Você foi atribuído à meta \"{$goalName}\".",
            Notification::TYPE_GOAL_DEADLINE  => "A meta \"{$goalName}\" vence em " . fake()->numberBetween(1, 7) . " dias.",
            Notification::TYPE_GOAL_COMPLETED => "A meta \"{$goalName}\" foi concluída com sucesso.",
        };
    }

    private function dataForType(string $type): array
    {
        $goalId = fake()->numberBetween(1, 50);

        return match ($type) {
            Notification::TYPE_GOAL_ASSIGNED => [
                'goal_id'   => $goalId,
                'assigned_by' => fake('pt_BR')->name(),
            ],
            Notification::TYPE_GOAL_DEADLINE => [
                'goal_id'      => $goalId,
                'deadline'     => fake()->dateTimeBetween('now', '+7 days')->format('Y-m-d'),
                'days_remaining' => fake()->numberBetween(1, 7),
            ],
            Notification::TYPE_GOAL_COMPLETED => [
                'goal_id'      => $goalId,
                'completed_by' => fake('pt_BR')->name(),
            ],
        };
    }
}
