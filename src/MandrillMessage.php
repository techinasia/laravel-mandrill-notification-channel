<?php

namespace NotificationChannels\Mandrill;

use DateTime;
use DateTimeZone;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class MandrillMessage implements Arrayable
{
    /** @var bool */
    protected $async;

    /** @var string */
    protected $ipPool;

    /** @var array */
    protected $message = [];

    /** @var string */
    protected $sendAt;

    /** @var array */
    protected $templateContent = [];

    /** @var string */
    protected $templateName;

    /**
     * Overload methods to have setters for message attributes.
     *
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}(...$arguments);
        }

        $value = ! empty($arguments) ? $arguments[0] : '';

        Arr::set($this->message, snake_case($name), $value);

        return $this;
    }

    /**
     * Sets async mode. Defaults to true if no value is set.
     *
     * @param  bool $value
     * @return $this
     */
    public function async($value = true)
    {
        $this->async = $value;
        return $this;
    }

    /**
     * Set the name of the IP pool that should be used to send the message.
     *
     * @param  string $name
     * @return $this
     */
    public function ipPool($name)
    {
        $this->ipPool = $name;
        return $this;
    }

    /**
     * Send the message at a specified date/time.
     *
     * @param  string|DateTime $datetime
     * @return $this
     */
    public function sendAt($datetime)
    {
        if (! $datetime instanceof DateTime) {
            $datetime = new DateTime($datetime);
        }

        // Mandrill API requires DateTime to be in UTC.
        $this->sendAt = $datetime
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Setter for message template.
     * If this is set, the channel will post via Mandrill's `send-message` call.
     *
     * @param  string $name
     * @param  array  $content
     * @return $this
     */
    public function template($name, array $content = [])
    {
        $this->templateName = $name;
        $this->templateContent = $content;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'message' => $this->message,
            'template_name' => $this->templateName,
            'template_content' => $this->templateContent,
            'async' => $this->async,
            'ip_pool' => $this->ipPool,
            'send_at' => $this->sendAt
        ];
    }
}
