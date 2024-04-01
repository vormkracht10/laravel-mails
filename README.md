# Laravel Mails

[![Total Downloads](https://img.shields.io/packagist/dt/vormkracht10/laravel-mails.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/laravel-mails)
[![Tests](https://github.com/vormkracht10/laravel-mails/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/vormkracht10/laravel-mails/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/vormkracht10/laravel-mails/actions/workflows/phpstan.yml/badge.svg?branch=main)](https://github.com/vormkracht10/laravel-mails/actions/workflows/phpstan.yml)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/vormkracht10/laravel-mails)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/vormkracht10/laravel-mails)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/vormkracht10/laravel-mails.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/laravel-mails)

Laravel Mails can collect everything you might want to track about the mails that has been sent by your Laravel app. Common use cases are provided in this package:

-   Log all sent emails with only specific attributes
-   View all sent emails in the browser using the viewer
-   Collect feedback about the delivery from email providers using webhooks
-   Relate sent emails to Eloquent models
-   Get automatically notified when email bounces
-   Prune logging of emails periodically
-   Resend logged email to another recipient

## Why this package

Email as a protocol is very error prone. Succesfull email delivery is not guaranteed in any way, so it is best to monitor your email sending realtime. Using external services like Postmark, Mailgun or Resend email gets better by offering things like logging and delivery feedback, but it still needs your attention and can fail silently but horendously. Therefore we created Laravel Mails that fills in all the gaps.

## Installation

You can install the package via composer:

```bash
composer require vormkracht10/laravel-mails
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="mails-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="mails-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="mails-views"
```

## Usage

```php
$laravelMails = new Vormkracht10\Mails();
echo $laravelMails->echoPhrase('Hello, Vormkracht10!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Mark van Eijk](https://github.com/markvaneijk)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
