# app version provider for Laravel 5

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

## About

`laravel-version-service`将添加用于查询代码的版本信息的接口。

## Installation

```bash
composer require jiejunf/laravel-version-service
```
如果 laravel 的版本 < 5.5 ，还需要将 VersionService\VersionServiceProvider 添加到 config/app.php 的 providers 数组中。

## Configuration

使用以下命令发布默认的配置文件到`config/version.php`

```php
php artisan vendor:publish --provider="Jiejunf\VersionService\VersionServiceProvider"
```
参数区分客户端及服务端，根据需求配置合适的参数

`app_type`参数为同平台多版本配置，如Android平台上有雇主/雇员、商户/顾客/企业之类的不同版本,若无多版本可填写单一版本或至空(为空数组时路由参数用`app`作为关键字代替)

`server_enable`参数为false时，服务端接口无法请求

> 注意：配置信息会影响迁移、路由、控制器

## Usage

- 客户端

    > 客户端接口需要迁移数据表，迁移前请根据项目配置好参数[参数信息](#configuration) 

    ```php
    php artisan migrate
    ```
    > 以下路由的参数均来自`config/version.php`
    
    获取最新版本
    
    - GET /{app_prefix}/{type}/{platform}/latest
    
    获取版本列表
    
    - GET /{app_prefix}/{type}/{platform}
    
    更新版本信息
    
    - PUT /{app_prefix}/
    - Body

         | name              | required | type   | desc 
         |-------------------|:--------:|:------:|--------------------------
         | platform          | required | string | 平台，必须是配置中的一个
         | app\_type         | required | string | 类型，必须是配置中的一个；没有配置时用app代替
         | app\_version      | required | string | 版本号，外部显示版本号
         | version\_code     | required | string | 版本号，内部迭代版本号
         | is\_force\_update |          | string | 必须是 y 或 n 中的一个，为空时默认为y
         | download\_path    |          | string | 此版本的下载地址
         | description       |          | string | 版本描述内容
         | version\_id       | required | string | 为空字符或历史版本中的一个

- 服务端
    
    > 服务端接口运行需要开启`exec(),shell_exec()`运行权限,且网络用户对项目目录具有正确的权限。[配置项目目录权限][link-permission]
    
    获取当前服务器的代码版本
    
    - GET /{server_prefix}
    
    拉取服务器远程主线代码

    > 网络用户需要配置远程仓库ssh公钥

    - POST /{server_prefix}/update
    
## License

根据MIT许可证发布, 见[LICENSE](LICENSE).
         
[ico-license]:https://img.shields.io/badge/license-MIT-green.svg
[ico-version]:https://img.shields.io/badge/version-v0.3-blue.svg
[ico-downloads]:https://img.shields.io/badge/downloads-<1M-green.svg

[link-downloads]:https://packagist.org/packages/jiejunf/laravel-version-service
[link-packagist]:https://packagist.org/packages/jiejunf/laravel-version-service
[link-permission]:https://vijayasankarn.wordpress.com/2017/02/04/securely-setting-file-permissions-for-laravel-framework/