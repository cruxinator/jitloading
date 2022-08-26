# Detect and dynamically repair Laravel model lazy-loads just-in-time

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cruxinator/jitloading.svg?style=flat-square)](https://packagist.org/packages/cruxinator/jitloading)
[![run-tests](https://github.com/cruxinator/laravel-strings/actions/workflows/run-tests.yml/badge.svg)](https://github.com/cruxinator/jitloading/actions/workflows/run-tests.yml)
[![Check & fix styling](https://github.com/cruxinator/laravel-strings/actions/workflows/php-cs-fixer.yml/badge.svg)](https://cruxinator/jitloading/laravel-strings/actions/workflows/php-cs-fixer.yml)
[![PHPStan](https://github.com/cruxinator/laravel-strings/actions/workflows/phpstan.yml/badge.svg)](https://github.com/cruxinator/jitloading/actions/workflows/phpstan.yml)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

## Installation

You can install the package via composer:

```bash
composer require cruxinator/jitloading
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="jitloading-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="jitloading-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="jitloading-views"
```

## Usage

```php
$jitLoading = new Cruxinator\JitLoading();
echo $jitLoading->echoPhrase('Hello, Cruxinator!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alex Goodwin](https://github.com/cruxinator)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
