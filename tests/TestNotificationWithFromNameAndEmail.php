<?php

namespace NotificationChannels\Mandrill\Tests;

use Illuminate\Notifications\Notification;
use NotificationChannels\Mandrill\MandrillMessage;

class TestNotificationWithFromNameAndEmail extends Notification
{
    const FROM_EMAIL = 'test@example.com';
    const FROM_NAME = 'Custom Sender';

    public function toMandrill($notifiable)
    {
        return (new MandrillMessage())
            ->fromEmail(self::FROM_EMAIL)
            ->fromName(self::FROM_NAME);
    }
}
