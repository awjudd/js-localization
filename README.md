# Laravel - JavaScript Localization

A simple localization library built for **Laravel** to take all of the resources defined for your project and make them accessible in JavaScript.

## Features

 * Compilation of the language resource files into a single JavaScript file
 * Resource replacements handled automatically

## Quick Start

In the `require` key of `composer.json` file add the following:

```
"awjudd/js-localization": "dev-master"
```

Run the Composer update command

```
$ composer update
```

In your `config/app.php` add `Awjudd\JavaScriptLocalization\ServiceProvider::class` to the end of the `$providers` array.

```php
'providers' => array(
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    ...
    Awjudd\JavaScriptLocalization\ServiceProvider::class,
),
```

## Setup

### Publishing the Configuration and Assets

After installing through composer, you should publish the config file. To do this, run the following command:

```
$ php artisan vendor:publish
```

### Configuration

Once published, you will be able to change the paths where the application looks however, this isn't necessary.

## License

JavaScript Localization is free software distributed under the terms of the MIT license

## Additional Information

Any issues, please [report here](https://github.com/awjudd/js-localization/issues)