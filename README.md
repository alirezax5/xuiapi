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
- [vaxilu](https://github.com/vaxilu/x-ui) =>  [Vaxilu.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/Vaxilu.php)
- [FranzKafkaYu](https://github.com/FranzKafkaYu/x-ui) =>  [FranzKafkaYu.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/FranzKafkaYu.php)
- [HexaSoftwareTech](https://github.com/HexaSoftwareTech/x-ui) =>  [HexaSoftwareTech.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/HexaSoftwareTech.php)
- [MHSanaei](https://github.com/MHSanaei/x-ui) , [alireza0](https://github.com/alireza0/x-ui) =>  [MHSanaei.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/MHSanaei.php)
- [NidukaAkalanka](https://github.com/NidukaAkalanka/x-ui-english) =>  [HexaSoftwareTech.php](https://github.com/alirezax5/xuiapi/blob/main/src/Panel/NidukaAkalanka.php)

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
