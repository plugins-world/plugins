# Tenant extension

[![Latest Version on Plugin](https://img.shields.io/packagist/v/plugins-world/tenant.svg?style=flat-square)](https://packagist.org/packages/plugins-world/tenant)
[![Build Status](https://img.shields.io/travis/plugins-world/tenant/master.svg?style=flat-square)](https://travis-ci.org/plugins-world/tenant)
[![Quality Score](https://img.shields.io/scrutinizer/g/plugins-world/tenant.svg?style=flat-square)](https://scrutinizer-ci.com/g/plugins-world/tenant)
[![Total Downloads](https://img.shields.io/packagist/dt/plugins-world/tenant.svg?style=flat-square)](https://packagist.org/packages/plugins-world/tenant)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
php artisan market:require plugins-world/laravel-saas # 通过应用市场管理器安装插件

php artisan saas:install # 需要配置数据库的 root 账号密码
```

## Usage

``` php
php artisan saas # 查看当前可以使用的与 saas 相关的指令
php artisan saas:demo-add --tenant=foo # 添加租户，默认添加名称为 foo 的租户
php artisan saas:demo-del --tenant=foo # 删除租户，默认删除名称为 foo 的租户
php artisan saas:list # 当前 saas 列表
php artisan tenants:migrate --tenants=foo # 迁移指定租户，用于开发阶段调试表结构与代码逻辑
php artisan tenants:migrate-rollback --tenants=foo # 回滚指定租户，用于开发阶段调试表结构与代码逻辑
php artisan ...
```

### Testing

``` bash
composer test
```

### How to create this package

`php artisan new Tenant`

Please see [plugin-manager](https://github.com/fresns/plugin-manager) for more information.
