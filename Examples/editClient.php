<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\Vaxilu('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->setCookie(__DIR__.'/a.txt');
$xui->login();

$xui->editClient('inbound id[int]','uuid or password[string]','email[string]','uuid or password[string]','traffic[int Gb]','time_ms[big int]','tgId[string]','subId[string]','limitIp[int]','fingerprint[string]','flow[string]');

$xui->editClientByEmail('inbound id[int]','email[string]','email[string]','uuid or password[string]','traffic[int Gb]','time_ms[big int]','tgId[string]','subId[string]','limitIp[int]','fingerprint[string]','flow[string]');

$xui->editClientWithKey('inbound id[int]','uuid or password[string]','key[string]','value');

$xui->editClientByEmailWithKey('inbound id[int]','email[string]','key[string]','value');


//exampel for editClientByEmailWithKey:

$xui->editClientByEmailWithKey(1,'alirezax5','email','newEmail');
