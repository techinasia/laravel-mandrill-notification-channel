<?php

namespace NotificationChannels\Mandrill\Tests;

use NotificationChannels\Mandrill\MandrillMessage;

class MandrillMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function testAsync()
    {
        $message = (new MandrillMessage())->async();
        $this->assertTrue($message->toArray()['async']);
    }

    /** @test */
    public function testAsyncFalse()
    {
        $message = (new MandrillMessage())->async(false);
        $this->assertFalse($message->toArray()['async']);
    }

    /** @test */
    public function testIpPool()
    {
        $message = (new MandrillMessage())->ipPool('foo');
        $this->assertEquals('foo', $message->toArray()['ip_pool']);
    }

    /** @test */
    public function testMethodOverloading()
    {
        $message = (new MandrillMessage())->fromEmail('zy@zy.sg');
        $this->assertEquals('zy@zy.sg', $message->toArray()['message']['from_email']);
    }

    /** @test */
    public function testSendAtWithDateTime()
    {
        $message = (new MandrillMessage())->sendAt(new \DateTime('2020-01-01'));
        $this->assertEquals('2020-01-01 00:00:00', $message->toArray()['send_at']);
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function testSendAtWithInvalidString()
    {
        $message = (new MandrillMessage())->sendAt('daosdjhaspdi');
    }

    /** @test */
    public function testSendAtWithValidString()
    {
        $message = (new MandrillMessage())->sendAt('2020-01-01');
        $this->assertEquals('2020-01-01 00:00:00', $message->toArray()['send_at']);
    }

    /** @test */
    public function testTemplate()
    {
        $message = (new MandrillMessage())->template('hello-world', ['foo' => 'bar']);
        $this->assertEquals('hello-world', $message->toArray()['template_name']);
        $this->assertEquals(['foo' => 'bar'], $message->toArray()['template_content']);
    }
}
