This is an exercise project to increase coding skills and the user is responsible for using it.

## About the project

It is a web service project to manage the x-ui panel

## Features

* Support for 90% of panels
* Xray management by API
* Show status
* Change xray version
* Inbound management
* Settings management
* User(admin) managementt.

## Install

``
composer require alirezax5/xuiapi
``

## Use  
```php 
<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\Vaxilu('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
#Set Up Cookie
$xui->setCookie(__DIR__.'/Cookie.txt');
$xui->login();
print_r($xui->listInbound());
```

## Example
[view Example](https://github.com/alirezax5/xuiapi/tree/main/Examples)
