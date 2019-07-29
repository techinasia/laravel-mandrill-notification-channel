<?php

namespace NotificationChannels\Mandrill\Tests;

use Mockery;
use NotificationChannels\Mandrill\MandrillChannel;
use Orchestra\Testbench\TestCase;

class MandrillChannelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('mail.from.address', 'foo@bar.com');
        $this->app['config']->set('mail.from.name', 'Test Name');

        $this->mandrillMessages = Mockery::mock(\Mandrill_Messages::class);

        $mandrill = Mockery::mock(\Mandrill::class);
        $mandrill->messages = $this->mandrillMessages;

        // Init curl for Mandrill so we don't get any errors when closing the
        // session when the destructor is being called (not mocked by Mockery).
        $mandrill->ch = curl_init();

        $this->channel = new MandrillChannel($mandrill);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function testSend()
    {
        $this->mandrillMessages->shouldReceive('send');
        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function testSendMultipleRecipients()
    {
        $this->mandrillMessages->shouldReceive('send')->with([
            'to' => [
                [
                    'email' => 'a@b.com',
                    'name' => 'User A',
                ],
                [
                    'email' => 'b@b.com',
                    'name' => 'User B',
                    'type' => 'cc',
                ],
            ],
            'from_email' => 'foo@bar.com',
            'from_name' => 'Test Name',
        ], false, null, null);
        $this->channel->send(new TestNotifiableMultipleRecipients(), new TestNotification());
    }

    /** @test */
    public function testSendTemplate()
    {
        $this->mandrillMessages->shouldReceive('sendTemplate');
        $this->channel->send(new TestNotifiable(), new TestNotificationWithTemplate());
    }

    /** @test */
    public function testSendTo()
    {
        $this->mandrillMessages->shouldReceive('send')->with([
            'to' => [['email' => 'a@b.com', 'name' => 'XYZ User']],
            'from_email' => 'foo@bar.com',
            'from_name' => 'Test Name',
        ], false, null, null);
        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function testSendWithForward()
    {
        $this->app['config']->set('services.mandrill.forward.enabled', true);
        $this->app['config']->set('services.mandrill.forward.email', 'foo@bar.com');

        $this->mandrillMessages->shouldReceive('send')->with([
            'to' => [['email' => 'foo@bar.com']],
            'from_email' => 'foo@bar.com',
            'from_name' => 'Test Name',
        ], false, null, null);

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function testSendWithFromNameAndEmail()
    {
        $this->mandrillMessages->shouldReceive('send')->with([
            'from_email' => TestNotificationWithFromNameAndEmail::FROM_EMAIL,
            'from_name' => TestNotificationWithFromNameAndEmail::FROM_NAME,
            'to' => [[
                'email' => 'a@b.com',
                'name' => 'XYZ User'
            ]],
        ], null, null, null);

        $this->channel->send(new TestNotifiable(), new TestNotificationWithFromNameAndEmail());
    }
}
