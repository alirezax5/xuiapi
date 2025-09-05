<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\MHSanaei('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->login();

//normal
$body = [
    'id' => 5,
    'settings' => '{"clients": [{"id": "bbfad557-28f2-47e5-9f3d-e3c7f532fbda","flow": "","email": "dp1plmlt8","limitIp": 0,"totalGB": 0,"expiryTime": 0,"enable": true,"tgId": "","subId": "2rv0gb458kbfl532","reset": 0}]}'
];
$xui->addClient($body);

//dto

$dto = new \alirezax5\XuiApi\DTO\addClientDto();
$dto->id = 5;
$dto->settings = '{"clients": [{"id": "bbfad557-28f2-47e5-9f3d-e3c7f532fbda","flow": "","email": "dp1plmlt8","limitIp": 0,"totalGB": 0,"expiryTime": 0,"enable": true,"tgId": "","subId": "2rv0gb458kbfl532","reset": 0}]}';
$xui->addClient($dto->toArray());

//builder
$builder = new \alirezax5\XuiApi\Builder\addClientBuilder();
$array = $builder->setId(5)->setSettings('{"clients": [{"id": "bbfad557-28f2-47e5-9f3d-e3c7f532fbda","flow": "","email": "dp1plmlt8","limitIp": 0,"totalGB": 0,"expiryTime": 0,"enable": true,"tgId": "","subId": "2rv0gb458kbfl532","reset": 0}]}')->build();
$xui->addClient($array);




