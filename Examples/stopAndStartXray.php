<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\Vaxilu('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->setCookie(__DIR__ . '/a.txt');
$xui->login();

//stop
$xui->stopXrayService();
// start
$xui->restartXrayService();
