<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationType::create([
            'name' => 'Email',
            'description' => 'Изпращане на уведомление чрез имейл'
        ]);

        NotificationType::create([
            'name' => 'SMS',
            'description' => 'Изпращане на уведомление чрез SMS'
        ]);

        NotificationType::create([
            'name' => 'Push',
            'description' => 'Изпращане на PUSH нотификация'
        ]);
    }
}
