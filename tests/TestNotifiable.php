<?php

namespace NotificationChannels\Mandrill\Tests;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /**
     * Route notifications for the Mandrill channel.
     *
     * @return array
     */
    public function routeNotificationForMandrill()
    {
        return [
            'email' => 'a@b.com',
            'name' => 'XYZ User',
        ];
    }
}
