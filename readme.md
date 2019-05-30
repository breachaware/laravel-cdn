# Laravel CDN Assets Manager

THIS PACKAGE IS STILL IN DEVELOPMENT.  DO NOT USE THIS PACKAGE IN PRODUCTION!

##### A Content Delivery Network package for Laravel
The package allows you to upload your static assets to AWS S3 with a single Artisan command.  Optionally, you may configure a CloudFront or CloudFlare URL.

to upload their assets (or any public file) to a CDN with a single artisan command.
And then it allows them to switch between the local and the online version of the files.

###### Fork From [Vinelab/cdn](https://github.com/Vinelab/cdn) and [publiux/laravelcdn](https://github.com/publiux/laravelcdn)
This project has been forked from https://github.com/publiux/laravelcdn, which was forked from https://github.com/Vinelab/cdn. All credit for the original work goes to Vinelab and publiux.

#### Laravel Support
- This package supports Laravel 5.5 up to an including Laravel 5.8 (`master`).
- This package supports Laravel's package auto-discovery.

## Highlights
- Support for AWS S3
- Support for AWS CloudFront
- Support for CloudFlare with an AWS S3 origin
- Artisan command to upload content to AWS S3
- A simple facade to access CDN assets including support for versioned assets using Laravel Mix.


### Questions
1. Is this package an alternative to Laravel FileSystem and do they work together?

+ No, the package was introduced in Laravel 4 and it's main purpose is to manage your CDN assets by loading them from the CDN into your Views pages, and easily switch between your Local and CDN version of the files. As well it allows you to upload all your assets with single command after specifying the assets directory and rules. The FileSystem was introduced in Laravel 5 and it's designed to facilitate the loading/uploading of files form/to a CDN. It can be used the same way as this Package for loading assets from the CDN, but it's harder to upload your assets to the CDN since it expect you to upload your files one by one. As a result this package still not a replacement of the Laravel FileSystem and they can be used together.


## Installation

#### Via Composer

Require `breachaware/laravel-cdn` in your project:

```bash
composer require breachaware/laravel-cdn
```

```php
'providers' => array(
    //...
    BreachAware\LaravelCdn\CdnServiceProvider::class,
),
```

```php
'aliases' => [
    //...
    'CDN' => BreachAware\LaravelCdn\Facades\CdnFacade::class,
],
```

*If you are using Laravel 5.5 or higher, there is no need to register the service provider or alias as this package is automatically discovered.*

Publish the package config file:

```bash
php artisan vendor:publish --provider "BreachAware\LaravelCdn\CdnServiceProvider"
```

## Environment Configuration

This package can be configured by editing the config/cdn.php file.  Alternatively, you can set many of these options in as environment variables in your '.env' file.

##### AWS Credentials
Set your AWS Credentials and other settings in the `.env` file.

*Note: you should always have an `.env` file at the project root, to hold your sensitive information. This file should usually not be committed to your VCS.*

```bash
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

##### CDN URL

Set the CDN URL:

```php
'url' => env('CDN_Url', 'https://s3.amazonaws.com'),
```

This can altered in your '.env' file as follows:

```bash
CDN_Url=
```

##### Bypass

To load your LOCAL assets for testing or during development, set the `bypass` option to `true`:

```php
'bypass' => env('CDN_Bypass', false),
```

This can be altered in your '.env' file as follows:

```bash
CDN_Bypass=
```

##### Cloudfront Support

```php
'cloudfront'    => [
    'use' => env('CDN_UseCloudFront', false),
    'cdn_url' => env('CDN_CloudFrontUrl', false)
],
```

This can be altered in your '.env' file as follows:

```bash
CDN_UseCloudFront=
CDN_CloudFrontUrl=
```

##### Default CDN Provider
For now, the only CDN provider available is AwsS3. Although, as DO natively support the AWS API, you can utilise it by also providing the endpoint, please see the cdn.php config for more info. This option cannot be set in '.env'.

```php
'default' => 'AwsS3',
```

##### CDN Provider Configuration

```php
'aws' => [

    's3' => [

        'version'   => 'latest',
        'region'    => '',
        'endpoint'  => '', // For DO Spaces

        'buckets' => [
            'my-backup-bucket' => '*',
        ]
    ]
],
```

###### Multiple Buckets

```php
'buckets' => [

    'my-default-bucket' => '*',

    // 'js-bucket' => ['public/js'],
    // 'css-bucket' => ['public/css'],
    // ...
]

```

#### Files & Directories

###### Include:

Specify directories, extensions, files and patterns to be uploaded.

```php
'include'    => [
    'directories'   => ['public/dist'],
    'extensions'    => ['.js', '.css', '.yxz'],
    'patterns'      => ['**/*.coffee'],
],
```

###### Exclude:

Specify what to be ignored.

```php
'exclude'    => [
    'directories'   => ['public/uploads'],
    'files'         => [''],
    'extensions'    => ['.TODO', '.txt'],
    'patterns'      => ['src/*', '.idea/*'],
    'hidden'        => true, // ignore hidden files
],
```




##### Other Configurations

```php
'acl'           => 'public-read',
'metadata'      => [ ],
'expires'       => gmdate("D, d M Y H:i:s T", strtotime("+5 years")),
'cache-control' => 'max-age=2628000',
```

You can always refer to the AWS S3 Documentation for more details: [aws-sdk-php](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/)

## Usage

You can 'push' your assets to your CDN and you can 'empty' your assets as well using the commands below.

#### Push

Only changed assets are pushed to the CDN. (THanks, )

Upload assets to CDN
```bash
php artisan cdn:push
```

You can specify a folder upload prefix in the cdn.php config file. Your assets will be uploaded into that folder on S3.

#### Empty

Delete assets from CDN
```bash
php artisan cdn:empty
```
CAUTION: This will erase your entire bucket. This may not be what you want if you are specifying an upload folder when you push your assets.

#### Retrieving Asset URLs

There are two primary options when retrieving the CDN URL of a static asset:

##### Unversioned assets
Use the `CDN` facade to call the `CDN::asset()` function.

*Note: the `asset` works the same as the Laravel `asset` it start looking for assets in the `public/` directory:*

```blade
{{ CDN::asset('css/app.css') }}     // example result: https://cdn.yourdomain.com/css/app.css
{{ CDN::asset('js/app.js') }}       // example result: https://cdn.yourdomain.com/js/app.js
```

##### Versioned assets using Laravel Mix
Use the `CDN` facade to call the `CDN::mix()` method.

*Note: the `mix` method works by parsing the `mix-manifest.json` file that is generated by webpack, the same as `Laravel Mix`.*

```blade
{{ CDN::mix('css/app.css') }}     // example result: https://cdn.yourdomain.com/css/app.css?id=2f7c00b6f09552c3f0ce
{{ CDN::mix('js/app.js') }}       // example result: https://cdn.yourdomain.com/js/app.js?id=dbd07402af699be7a212
```

To use a file from outside the `public/` directory, anywhere in `app/` use the `CDN::path()` function:

```blade
{{CDN::path('private/something/file.txt')}}        // example result: https://css-bucket.s3.amazonaws.com/private/something/file.txt
```

## Test
To run the tests, run the following command from the project folder.

```bash
$ ./vendor/bin/phpunit
```

## Support
Please request support or submit issues [via Github](https://github.com/breachaware/laravel-cdn/issues)

## Contributing
Please see [CONTRIBUTING](https://github.com/breachaware/laravel-cdn/blob/master/CONTRIBUTING.md) for details.

## Security Related Issues
If you discover any security related issues, please do not raise a Github issue.  Instead, please email brent.kozjak+github@gmail.com with the details of the vulnerability.

## Credits
- [Mahmoud Zalt](https://github.com/Mahmoudz) (original developer)
- [Raul Ruiz](https://github.com/publiux) (pre-fork contributor)
- [Filipe Garcia](https://github.com/filipegar) (pre-fork contributor)
- [Contributors from original project](https://github.com/Vinelab/cdn/graphs/contributors)
- [Brent Kozjak](https://github.com/brentkozjak) (current maintainer)

## License
The MIT License (MIT). Please see [License File](https://github.com/breachaware/laravel-cdn/blob/master/LICENSE) for more information.

## Changelog

#### v1.0.0
- Initial release from BreachAware

*Note: The version history has been reset since forking from the original package.*
