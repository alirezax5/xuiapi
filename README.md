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
## Panels
- [MHSanaei](https://github.com/MHSanaei/3x-ui)  =>  [MHSanaei.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/MHSanaei.php)
- [alireza0](https://github.com/alireza0/x-ui) =>  [Alireza0.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/Alireza0.php)

## Use  
```php 
<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\MHSanaei('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
#Set Up Cookie
$xui->login();
print_r($xui->listInbound());
```

## Example
[view Example](https://github.com/alirezax5/xuiapi/tree/main/Examples)


## Donation
USDT (TRC20) & TRX

``
TQk6AHMREwER9EyGzhUsVv2hUQygGMyCeT
``

TON

``
EQBnlnOGefCkwgtO7IZdOBFuoojkpKgK3mI1GmH3MH_gGx34
``
