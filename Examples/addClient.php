<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\Vaxilu('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->setCookie(__DIR__.'/a.txt');
$xui->login();

var_dump($xui->addnewClient('inbound id[int]','uuid or password[string]','email[string]','subId[string]','tgid[string]','flow[string]','traffic[int Gb]','time_ms[big int]','limitIp[int]','fingerprint[string]','isTrojan[bool]'));


// php 8
$xui->addnewClient(id:123,uuid: 'uuid',email: 'email',totalgb: 0,eT: 0);
