<?php
require __DIR__ . '/vendor/autoload.php';
$xui = new \alirezax5\XuiApi\Panel\MHSanaei('YOU_PANEL_URL', 'YOU_PANEL_USERNAME', 'YOU_PANEL_PASSWORD');
$xui->login();

//normal
$body = [
    'up' => 0,
    'down' => 0,
    'total' => 0,
    'remark' => 'New',
    'enable' => true,
    'expiryTime' => 0,
    'listen' => '',
    'port' => 55421,
    'protocol' => 'vless',
    'settings' => '{"clients": [{"id": "b86c0cdc-8a02-4da4-8693-72ba27005587","flow": "","email": "nt3wz904","limitIp": 0,"totalGB": 0,"expiryTime": 0,"enable": true,"tgId": "","subId": "rqv5zw1ydutamcp0","reset": 0}],"decryption": "none","fallbacks": []}',
    'streamSettings' => '{"network": "tcp","security": "reality","externalProxy": [],"realitySettings": {"show": false,"xver": 0,"dest": "yahoo.com:443","serverNames": ["yahoo.com","www.yahoo.com"],"privateKey": "wIc7zBUiTXBGxM7S7wl0nCZ663OAvzTDNqS7-bsxV3A","minClient": "","maxClient": "","maxTimediff": 0,"shortIds": ["47595474","7a5e30","810c1efd750030e8","99","9c19c134b8","35fd","2409c639a707b4","c98fc6b39f45"],"settings": {"publicKey": "2UqLjQFhlvLcY7VzaKRotIDQFOgAJe1dYD1njigp9wk","fingerprint": "random","serverName": "","spiderX": "/"}},"tcpSettings": {"acceptProxyProtocol": false,"header": {"type": "none"}}}',
    'sniffing' => '{"enabled": true,"destOverride": ["http","tls","quic","fakedns"],"metadataOnly": false,"routeOnly": false}',
    'allocate' => '{"strategy": "always","refresh": 5,"concurrency": 3}'
];;
$xui->addInbound($body);

//dto

$dto = new \alirezax5\XuiApi\DTO\addInboundDto();
$dto->total = 500;
$dto->remark = 'new';
$dto->expiryTime = 99999;
$dto->port = 8080;
$dto->protocol = 'vless';
$dto->settings = '{"clients": [{"id": "b86c0cdc-8a02-4da4-8693-72ba27005587","flow": "","email": "nt3wz904","limitIp": 0,"totalGB": 0,"expiryTime": 0,"enable": true,"tgId": "","subId": "rqv5zw1ydutamcp0","reset": 0}],"decryption": "none","fallbacks": []}';
$dto->streamSettings = '{"network": "tcp","security": "reality","externalProxy": [],"realitySettings": {"show": false,"xver": 0,"dest": "yahoo.com:443","serverNames": ["yahoo.com","www.yahoo.com"],"privateKey": "wIc7zBUiTXBGxM7S7wl0nCZ663OAvzTDNqS7-bsxV3A","minClient": "","maxClient": "","maxTimediff": 0,"shortIds": ["47595474","7a5e30","810c1efd750030e8","99","9c19c134b8","35fd","2409c639a707b4","c98fc6b39f45"],"settings": {"publicKey": "2UqLjQFhlvLcY7VzaKRotIDQFOgAJe1dYD1njigp9wk","fingerprint": "random","serverName": "","spiderX": "/"}},"tcpSettings": {"acceptProxyProtocol": false,"header": {"type": "none"}}}';
$dto->sniffing = '{"enabled": true,"destOverride": ["http","tls","quic","fakedns"],"metadataOnly": false,"routeOnly": false}';
$dto->allocate = '{"strategy": "always","refresh": 5,"concurrency": 3}';
$xui->addInbound($dto->toArray());

//builder
$builder = new \alirezax5\XuiApi\Builder\addInboundBuilder();
$array = $builder->setRemark('new')
    ->setProtocol('vless')
    ->setPort(8080)
    ->setExpiryTime(9999)
    ->setSettings('{"clients": [{"id": "bbfad557-28f2-47e5-9f3d-e3c7f532fbda","flow": "","email": "dp1plmlt8","limitIp": 0,"totalGB": 0,"expiryTime": 0,"enable": true,"tgId": "","subId": "2rv0gb458kbfl532","reset": 0}]}')
    ->setStreamSettings('{"network": "tcp","security": "reality","externalProxy": [],"realitySettings": {"show": false,"xver": 0,"dest": "yahoo.com:443","serverNames": ["yahoo.com","www.yahoo.com"],"privateKey": "wIc7zBUiTXBGxM7S7wl0nCZ663OAvzTDNqS7-bsxV3A","minClient": "","maxClient": "","maxTimediff": 0,"shortIds": ["47595474","7a5e30","810c1efd750030e8","99","9c19c134b8","35fd","2409c639a707b4","c98fc6b39f45"],"settings": {"publicKey": "2UqLjQFhlvLcY7VzaKRotIDQFOgAJe1dYD1njigp9wk","fingerprint": "random","serverName": "","spiderX": "/"}},"tcpSettings": {"acceptProxyProtocol": false,"header": {"type": "none"}}}')
    ->setSniffing('{"enabled": true,"destOverride": ["http","tls","quic","fakedns"],"metadataOnly": false,"routeOnly": false}')
    ->setAllocate('{"strategy": "always","refresh": 5,"concurrency": 3}')
    ->build();
$xui->addInbound($array);




