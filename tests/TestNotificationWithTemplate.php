<?php

namespace NotificationChannels\Mandrill\Tests;

use Illuminate\Notifications\Notification;
use NotificationChannels\Mandrill\MandrillMessage;

class TestNotificationWithTemplate extends Notification
{
    public function toMandrill($notifiable)
    {
        return (new MandrillMessage())
            ->template('hello-world', ['foo' => 'bar']);
    }
}
