<?php

namespace NotificationChannels\Mandrill\Tests;

use Illuminate\Notifications\Notification;
use NotificationChannels\Mandrill\MandrillMessage;

class TestNotification extends Notification
{
    public function toMandrill($notifiable)
    {
        return new MandrillMessage();
    }
}
