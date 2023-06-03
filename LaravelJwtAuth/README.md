# LaravelJwtAuth

[![Latest Stable Version](http://poser.pugx.org/plugins-world/laravel-jwt-auth/v)](https://packagist.org/packages/plugins-world/laravel-jwt-auth)
[![Total Downloads](http://poser.pugx.org/plugins-world/laravel-jwt-auth/downloads)](https://packagist.org/packages/plugins-world/laravel-jwt-auth)
[![Latest Unstable Version](http://poser.pugx.org/plugins-world/laravel-jwt-auth/v/unstable)](https://packagist.org/packages/plugins-world/laravel-jwt-auth) [![License](http://poser.pugx.org/plugins-world/laravel-jwt-auth/license)](https://packagist.org/packages/plugins-world/laravel-jwt-auth)
[![PHP Version Require](http://poser.pugx.org/plugins-world/laravel-jwt-auth/require/php)](https://packagist.org/packages/plugins-world/laravel-jwt-auth)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
php artisan market:require plugins-world/laravel-jwt-auth

composer require plugins-world/laravel-jwt-auth
```

## Usage

单独使用登录功能时，请在 `auth()->login()` 时使用 Plugins\LaravelJwtAuth\Models\User，示例如下：

```php
use Plugins\LaravelJwtAuth\Models\User as JwtModelUser;

$inputPassword = \request('password');

$user = \App\Models\User::first();
if (Hash::check($user->password, $inputPassword)) {
    // get_class($user) !== config('auth.providers.api.model') 配置的模型时，
    // 需要将 $user 转换成配置的模型。后续验证才能通过登录验证。
    $token = auth('api')->login(new JwtModelUser($user->toArray()));
}
```

### Testing

``` bash
composer test
```

### How to create this package

`php artisan new LaravelJwtAuth`

Please see [plugin-manager](https://github.com/plugins-world/plugin-manager) for more information.
