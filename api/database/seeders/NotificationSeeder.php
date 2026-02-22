<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando notificações...');

        $users = User::where('active', true)->get();

        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário ativo encontrado. Execute UsersSeeder primeiro.');
            return;
        }

        // Notificações de meta atribuída (não lidas)
        Notification::factory()
            ->count(10)
            ->goalAssigned()
            ->unread()
            ->sequence(fn () => ['user_id' => $users->random()->id])
            ->create();

        $this->command->info('- 10 notificações de meta atribuída (não lidas) criadas');

        // Notificações de prazo se aproximando (não lidas)
        Notification::factory()
            ->count(8)
            ->goalDeadline()
            ->unread()
            ->sequence(fn () => ['user_id' => $users->random()->id])
            ->create();

        $this->command->info('- 8 notificações de prazo de meta (não lidas) criadas');

        // Notificações de meta concluída (lidas)
        Notification::factory()
            ->count(12)
            ->goalCompleted()
            ->read()
            ->sequence(fn () => ['user_id' => $users->random()->id])
            ->create();

        $this->command->info('- 12 notificações de meta concluída (lidas) criadas');

        // Mix variado de notificações
        Notification::factory()
            ->count(20)
            ->sequence(fn () => ['user_id' => $users->random()->id])
            ->create();

        $this->command->info('- 20 notificações variadas criadas');

        $this->command->info('Total: ' . Notification::count() . ' notificações criadas!');
    }
}
