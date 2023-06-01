# Tenant extension

[![Latest Version on Plugin](https://img.shields.io/packagist/v/plugins-world/tenant.svg?style=flat-square)](https://packagist.org/packages/plugins-world/tenant)
[![Build Status](https://img.shields.io/travis/plugins-world/tenant/master.svg?style=flat-square)](https://travis-ci.org/plugins-world/tenant)
[![Quality Score](https://img.shields.io/scrutinizer/g/plugins-world/tenant.svg?style=flat-square)](https://scrutinizer-ci.com/g/plugins-world/tenant)
[![Total Downloads](https://img.shields.io/packagist/dt/plugins-world/tenant.svg?style=flat-square)](https://packagist.org/packages/plugins-world/tenant)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require plugins-world/tenant

php artisan tenancy:install

php artisan saas:install # 需要配置数据库的 root 账号密码
```

## Usage

``` php
php artisan saas
php artisan saas:demo-add
php artisan saas:demo-del
php artisan saas:list
php artisan tenants:migrate --tenants=foo
php artisan ...
```

### Testing

``` bash
composer test
```

### How to create this package

`php artisan new Tenant`

Please see [plugin-manager](https://github.com/fresns/plugin-manager) for more information.
