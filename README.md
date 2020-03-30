# laravel-multilanguage
## Installation:
**add in file composer.json**
```
"require": {
	"megaads/laravel-multilanguage": "^1.0.2"
}
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
Go http://domain/lang-editor
```
