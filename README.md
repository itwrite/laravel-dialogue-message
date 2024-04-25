# laravel-dialogue-message
version 1.0 message service

## Installation
`laravel-dialogue-message` can be installed via composer:
```shell
composer require itwri/laravel-dialogue-message
```
The package will automatically register a service provider.

This package comes with a migration to store dialogue and message's data. You can publish the migration file using:
```shell
php artisan vendor:publish --provider="Itwri\DialogueMessageService\DialogueMessageServiceProvider" --tag="migrations"
```

Run the migrations with:
```shell
php artisan migrate
```

Next, you need to publish the dialogue configuration file:
```shell
php artisan vendor:publish --provider="Itwri\DialogueMessageService\DialogueMessageServiceProvider" --tag="config"
```

## Other
In your `config/app.php` add `Prettus\Repository\Providers\RepositoryServiceProvider::class` to the end of the providers array:
```php
'providers' => [
    ...
    Itwri\DialogueMessageService\DialogueMessageServiceProvider::class,
],
```
