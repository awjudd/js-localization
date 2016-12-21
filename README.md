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

After installing through composer, you should publish the configuration file as well as a single JavaScript file. To do this, run the following command:

```
$ php artisan vendor:publish
```

You must include `vendor/js-localization/resources.js` JavaScript file on your page in order to gain use of the necessary helpers.

By default, the console application will write the resources (for each locale) out to the `storage/public/lang` folder.  This will then be made publically accessible if you use Laravel's built in link command (`php artisan storage:link`).

To ensure that the latest version of the resource file is provided to the users, you may want to consider adding the following to your `gulpfile.js`.

*Please Note*: You will need to list any locales you have here.

```js
elixir((mix) => {
    mix.version(['storage/lang/resources-en.js']);
});
```

Then, on your web page include the following:

```
<script src="{{ elixir('storage/lang/resources-{desired-locale}.js') }}"></script>
```

You will need to replace `{desired-locale}` with the name of the locale that the user is requesting.

### Configuration

Once published, you will be able to change the paths where the application looks however, this isn't necessary.


### Using the Library

When loaded, this library will create an object on the default `window` object.  This object is named `Resources`.  To access localized values, all you need to do is the following:

```js
Resources.get('array.dotted.notation.for.folder.structure.value', {
    'foo': 'bar',
})
```

This will find the required resource and apply any replacements if it is a string, where the replacements are the second parameter of this function call.
## License

JavaScript Localization is free software distributed under the terms of the MIT license

## Additional Information

Any issues, please [report here](https://github.com/awjudd/js-localization/issues)