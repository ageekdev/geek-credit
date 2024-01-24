<h1 align="center">Geek Credit</h1>

[![Laravel 10.x](https://img.shields.io/badge/Laravel-10.x-red.svg?style=flat-square)](http://laravel.com/docs/10.x)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ageekdev/geek-credit/run-tests.yml?label=tests&style=flat-square)](https://github.com/ageekdev/geek-credit/actions/workflows/run-tests.yml)

The Geek Credit Package simplifies credit management and in-app purchases in Laravel apps. With transaction history and customizable features, it's ideal for implementing credit-based systems.

## Installation

You can install the package via composer:

```bash
composer require ageekdev/geek-credit
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Ageekdev\GeekCredit\GeekCreditServiceProvider" --tag="geek-credit-config"
```

You can publish the migration with:

```bash
php artisan vendor:publish --provider="Ageekdev\GeekCredit\GeekCreditServiceProvider" --tag="geek-credit-migrations"
```
## Using Credit

Add the HasCredit trait on App\User model or any model who acts as user in your app.

```php
use Illuminate\Database\Eloquent\Model;
use Ageekdev\GeekCredit\Traits\HasCredit;

class UserModel extends Model
{
    use HasCredit;

    ...
}
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

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
