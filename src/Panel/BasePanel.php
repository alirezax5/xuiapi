<?php

namespace alirezax5\XuiApi\Panel;

abstract class BasePanel
{
    protected array $paths = [
        'login' => '/login',
        'status' => '/server/status',
        'getConfigJson' => '/server/getConfigJson',
        'getDb' => '/server/getDb',
        'getNewX25519Cert' => '/server/getNewX25519Cert',
        'restartXrayService' => '/server/restartXrayService',
        'stopXrayService' => '/server/stopXrayService',
        'getXrayVersion' => '/server/getXrayVersion',
        'installXray' => '/server/installXray/{id}',
        'logs' => '/server/logs',
        'restartPanel' => '/setting/restartPanel',
        'allSetting' => '/xui/setting/all',
        'updateSetting' => '/xui/setting/update',
        'updateUser' => '/xui/setting/updateUser',
        'listInbound' => '/xui/inbound/list',
        'inbound' => '/xui/inbound/get/{id}',
        'delInbound' => '/xui/inbound/del/{id}',
        'updateInbound' => '/xui/inbound/update/{id}',
        'addInbound' => '/xui/inbound/add',
        'addClient' => '/xui/inbound/addClient/',
        'delClient' => '/xui/inbound/delClient/{id}',
        'resetClientTraffic' => '/xui/inbound/{id}/resetClientTraffic/{client}',
        'updateClient' => '/xui/inbound/updateClient/{id}',
        'clientIps' => '/xui/inbound/clientIps/{id}',
        'clearClientIps' => '/xui/clearClientIps/{id}',
    ];

    protected $defaults = [
        'sniffing' => [
            "enabled" => true,
            "destOverride" => [
                "http",
                "tls",
                "quic"
            ]
        ],
    ];
    protected $endpointWithId = ['delInbound', 'inbound', 'updateInbound', 'installXray', 'updateClient', 'clientIps', 'clearClientIps'];
    protected $endpointWithClient = ['resetClientTraffic', 'delClient'];


    public function getPath(string $key): ?string
    {
        return $this->paths[$key] ?? null;
    }
}