# laravel-multilanguage
## Installation:
**add in file composer.json**
```
composer require megaads/laravel-multilanguage
```
## Usage:
**Register Provider**
```
# /Config/app.php
'providers' => [
    Megaads\MultiLanguage\MultiLanguageServiceProvider::class
];

# /Config/auth.php:
'basicAuthentication' => [
        'username' => 'admin',
        'password' => 'admin'
    ]
```
****
```
Genereta lang file:

php artisan lang:generate

Go http://domain/lang-editor
```
