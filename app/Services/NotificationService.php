<?php

namespace App\Services;

use App\Models\Appointment;

/*
Знам че може да се направи с полиформизъм за да стане модулно с цел по лесно разширяване в бъдеще но предвид контекста на задачата
Реших да го направя със switch
*/

class NotificationService
{
    public function sendNotification(Appointment $appointment): string
    {
        $appointment->load('notificationType', 'client');
        $notificationType = $appointment->notificationType->name;
        
        switch ($notificationType) {
            case 'Email':
                return $this->sendEmailNotification($appointment);
            case 'SMS':
                return $this->sendSmsNotification($appointment);
            case 'Push':
                return $this->sendPushNotification($appointment);
            default:
                return "Неизвестен тип нотификация.";
        }
    }

    private function sendEmailNotification(Appointment $appointment): string
    {
        // Бъдеща имплементация за изпращане на имейл
        return "Клиентът ще бъде уведомен чрез Email.";
    }

    private function sendSmsNotification(Appointment $appointment): string
    {
        // Бъдеща имплементация за изпращане на SMS
        return "Клиентът ще бъде уведомен чрез SMS.";
    }

    private function sendPushNotification(Appointment $appointment): string
    {
        // Бъдеща имплементация за изпращане на PUSH нотификация
        return "Клиентът ще бъде уведомен чрез PUSH нотификация.";
    }
}