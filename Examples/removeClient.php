<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\MHSanaei('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->setCookie(__DIR__.'/a.txt');
$xui->login();


 $xui->removeClient('inbound id[int]','uuid or password[string]');

 $xui->removeClientByEmail('inbound id[int]','email[string]');
