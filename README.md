# Mandrill Notification Channel for Laravel

[![Dependency Status](https://gemnasium.com/techinasia/laravel-mandrill-notification-channel.svg)](https://gemnasium.com/techinasia/laravel-mandrill-notification-channel)
[![Build Status](https://travis-ci.org/techinasia/laravel-mandrill-notification-channel.svg)](https://travis-ci.org/techinasia/laravel-mandrill-notification-channel)
[![Coverage Status](https://coveralls.io/repos/github/techinasia/laravel-mandrill-notification-channel/badge.svg)](https://coveralls.io/github/techinasia/laravel-mandrill-notification-channel)
[![StyleCI Status](https://styleci.io/repos/75810155/shield)](https://styleci.io/repos/75810155)

> Use Laravel 5.3 notifications to send mail via Mandrill.

## Contents
- [Installation](#installation)
    - [Setting up Mandrill](#setting-up-mandrill)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation
Install this package with Composer:
``` bash
composer require techinasia/laravel-mandrill-notification-channel
```

Register the service provider in your `config/app.php`:
``` php
NotificationChannels\Mandrill\MandrillServiceProvider::class
```

### Setting up Mandrill
Add your API key to your configuration at `config/services.php`:
``` php 
'mandrill' => [
    'secret' => env('MANDRILL_SECRET', ''),
],
```

## Usage
Send mails via Mandrill in your notification:

``` php
use NotificationChannels\Mandrill\MandrillChannel;
use NotificationChannels\Mandrill\MandrillMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    public function via($notifiable)
    {
        return [MandrillChannel::class];
    }

    public function toMandrill($notifiable)
    {
        return (new MandrillMessage())
            ->template('foo-bar', ['foo' => 'bar']);
    }
}
```

You need to specify the email address and name of the notifiable by defining a `routeNotificationForMandrill` method on the entity:

``` php
/**
 * Route notifications for the Mandrill channel.
 *
 * @return array
 */
public function routeNotificationForMandrill()
{
    return [
        'email' => $this->email,
        'name' => $this->name
    ];
}
```

You can also specify multiple recipients by supplying an list similar to the `to[]` struct of [Mandrill API](https://mandrillapp.com/api/docs/messages.php.html):

``` php
/**
 * Route notifications for the Mandrill channel.
 *
 * @return array
 */
public function routeNotificationForMandrill()
{
    return [
        [
            'email' => 'a@bar.com',
            'name' => 'User A',
            'type' => 'to',
        ],
        [
            'email' => 'b@bar.com',
            'name' => 'User B',
            'type' => 'cc',
        ]
    ];
}
```

### Available Message methods
- `template(string $name, array $content)`: Sets the template name and content of the message. If this is set, `send-template` will be used instead of `send`.

The following methods work the same as the parameters in `send` and `send-template` calls from the [Mandrill API](https://mandrillapp.com/api/docs/messages.php.html).

- `async(bool $value = true)`: Enable a background sending mode that is optimized for bulk sending.
- `ip_pool(string $name)`: Name of the dedicated ip pool that should be used to send the message.
- `send_at(DateTime $datetime)`: Date / time of when to send the message. Object will be converted to UTC.

You can set any attributes of the `message` struct by calling the name of attribute in camel case with the value as the parameter:

``` php
return (new MandrillMessage())
    ->subject('Test Subject')
    ->mergeLanguage('handlebars');
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information for what has changed recently.

## Testing
``` bash
composer test
```

## Security
If you discover any security related issues, please email dev@techinasia.com instead of using the issues tracker.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
