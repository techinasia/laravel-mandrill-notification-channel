<?php

namespace NotificationChannels\Mandrill\Tests;

use Illuminate\Notifications\Notifiable;

class TestNotifiableMultipleRecipients
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
            [
                'email' => 'a@b.com',
                'name' => 'User A',
            ],
            [
                'email' => 'b@b.com',
                'name' => 'User B',
                'type' => 'cc',
            ],
        ];
    }
}
