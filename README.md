# Laravel 5 Activity Log

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This laravel 5 package provides to log the activity of users. It can also automatically log model events, custom message log. You can custom log handler to saving another destination
Unless you can use built in handler as EloquentHandler (using database ) or LogHandler (using log)

## Install

Via Composer

``` bash
$ composer require siduko/laravel-activitylog
```

Or add `composer.json`

``` json
"require": {
    '...',
    "siduko/laravel-activitylog":"v1.0-dev"
}
```

## Usage

Install the service provider to `config.php`
``` php
// config/app.php

'providers' => [
    '...',
    \LaiVu\ActivityLog\ActivityLogServiceProvider::class,
],

'aliases' => [
    '...',
    'ActivityLog'=>\LaiVu\ActivityLog\ActivityLogFacade::class,
],
```

You can publish the migration with:

``` bash
php artisan vendor:publish --provider="LaiVu\ActivityLog\ActivityLogServiceProvider" --tag="migrations"
```

After you run migration

``` bash
php artisan migrate
```

You can optionally publish the config file with:

``` bash
php artisan vendor:publish --provider="LaiVu\ActivityLog\ActivityLogServiceProvider" --tag="config"
```

Default config

``` php
<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 3:52 PM
 */

return [
    /***
     * When set to true, activity log will be active
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    'activity_model' => '\LaiVu\ActivityLog\Models\Activity',

    'default_log_name' => 'default',

    /***
     * Default activity log handle, using to setting log handler
     * You can custom a handler and set to here
     *  Example:
     *   'default' => ['eloquent','log','custom']
     */
    'default' => ['eloquent'],

    /**
     * When set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    'delete_records_older_than_days' => 365,

    /***
     * List log handlers, you can add new custom handler
     * `driver` is classpath of log handler
     */
    'handlers' => [
        'log' => [
            'driver' => '\LaiVu\ActivityLog\Handlers\LogHandler'
        ],
        'eloquent' => [
            'driver' => '\LaiVu\ActivityLog\Handlers\EloquentHandler'
        ]
    ]
];
```

### Basic Usage

This is the most basic way to log activity:

``` php
activity()->log('Look mum, I logged something');
```
Or

``` php
ActivityLog::log('Look mum, I logged something');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email laivu.fly@gmail.com instead of using the issue tracker.

## Credits

- [Lai Duy Hoang Vu][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/siduko/laravel-activitylog.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/siduko/laravel-activitylog/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/siduko/laravel-activitylog.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/siduko/laravel-activitylog.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/siduko/laravel-activitylog.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/siduko/laravel-activitylog
[link-travis]: https://travis-ci.org/siduko/laravel-activitylog
[link-scrutinizer]: https://scrutinizer-ci.com/g/siduko/laravel-activitylog/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/siduko/laravel-activitylog
[link-downloads]: https://packagist.org/packages/siduko/laravel-activitylog
[link-author]: https://github.com/siduko
[link-contributors]: ../../contributors
