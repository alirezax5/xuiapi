<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\MHSanaei('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->login();


 $xui->delClient('uuid or password[string]');

