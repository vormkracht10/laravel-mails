# Laravel Mails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vormkracht10/laravel-mails.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/laravel-mails)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vormkracht10/laravel-mails/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vormkracht10/laravel-mails/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vormkracht10/laravel-mails/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vormkracht10/laravel-mails/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vormkracht10/laravel-mails.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/laravel-mails)

Laravel Mails can collect everything you might want to track about the mails that has been sent by your Laravel app. Common use cases are provided in this package:

-   Log all sent emails with only specific attributes
-   Collect feedback about the delivery from email providers using webhooks
-   Relate sent emails to Eloquent models
-   Get automatically notified when email bounces
-   Prune logging of emails periodically
-   Resend logged email to another recipient

## Installation

You can install the package via composer:

```bash
composer require vormkracht10/laravel-mails
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-mails-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-mails-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-mails-views"
```

## Usage

```php
$laravelMails = new Vormkracht10\LaravelMails();
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
