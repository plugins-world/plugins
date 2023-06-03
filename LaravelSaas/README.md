# LaravelSaas

[![Latest Version on Plugin](https://img.shields.io/packagist/v/plugins-world/laravel-saas.svg?style=flat-square)](https://packagist.org/packages/plugins-world/laravel-saas)
[![Build Status](https://img.shields.io/travis/plugins-world/laravel-saas/master.svg?style=flat-square)](https://travis-ci.org/plugins-world/laravel-saas)
[![Quality Score](https://img.shields.io/scrutinizer/g/plugins-world/laravel-saas.svg?style=flat-square)](https://scrutinizer-ci.com/g/plugins-world/laravel-saas)
[![Total Downloads](https://img.shields.io/packagist/dt/plugins-world/laravel-saas.svg?style=flat-square)](https://packagist.org/packages/plugins-world/laravel-saas)


[![License](http://poser.pugx.org/plugins-world/laravel-saas/license)](https://packagist.org/packages/plugins-world/laravel-saas)

## 介绍

在最新的 laravel 框架中使用 saas 功能的项目。

依赖项目：
- [插件管理器 fresns/plugin-manager](https://pm.fresns.org/zh-Hans/)
- [应用市场管理器 fresns/market-manager](https://gitee.com/fresns/market-manager)
- [Tenancy 3.x](https://tenancyforlaravel.com/)
- [Laravel](https://laravel.com/)

## 前置要求

- Laravel 9+
- Tenancy 3+
- fresns/plugin-manager ^2
- fresns/market-manager ^1
- fresns/cmd-word-manager ^1
- 项目已完成 fresns/plugin-manager、fresns/market-manager 的安装。点击查看[如何安装插件管理器与应用市场管理器？](https://discuss.plugins-world.cn/post/hYJORaBi)

## 安装

```bash
php artisan market:require plugins-world/laravel-saas # 通过应用市场管理器安装插件

php artisan saas:install # 需要配置数据库的 root 账号密码
```

## 使用

``` php
php artisan saas # 查看当前可以使用的与 saas 相关的指令
php artisan saas:demo-add --tenant=foo # 添加租户，默认添加名称为 foo 的租户
php artisan saas:demo-del --tenant=foo # 删除租户，默认删除名称为 foo 的租户
php artisan saas:list # 当前 saas 列表
php artisan tenants:migrate --tenants=foo # 迁移指定租户，用于开发阶段调试表结构与代码逻辑
php artisan tenants:migrate-rollback --tenants=foo # 回滚指定租户，用于开发阶段调试表结构与代码逻辑
php artisan ...
```

### 这个包如何被创建的？

`php artisan new Tenant`

Please see [plugin-manager](https://github.com/fresns/plugin-manager) for more information.
