<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XuiApi\Traits\Additions;

class Alireza0 extends Base
{
    use Additions;

    protected $path = [
        'addClient' => '/xui/inbound/addClient/',
        'addInbound' => '/xui/inbound/add',
        'allSetting' => '/xui/setting/all',
        'api_delDepletedClients' => '/xui/API/inbounds/delDepletedClients/{id}',
        'api_get' => '/xui/API/inbounds/get/{id}',
        'api_getClientTraffics' => '/xui/API/inbounds/getClientTraffics/{id}',
        'api_getClientTrafficsById' => '/xui/API/inbounds/getClientTrafficsById/{id}',
        'api_list' => '/xui/API/inbounds/list/',
        'api_resetAllClientTraffics' => '/xui/API/inbounds/resetAllClientTraffics/{id}',
        'clearClientIps' => '/xui/clearClientIps/{id}',
        'clientIps' => '/xui/inbound/clientIps/{id}',
        'delClient' => '/xui/inbound/{id}/delClient/{client}',
        'delInbound' => '/xui/inbound/del/{id}',
        'getConfigJson' => '/server/getConfigJson',
        'getDb' => '/server/getDb',
        'getNewX25519Cert' => '/server/getNewX25519Cert',
        'getXrayVersion' => '/server/getXrayVersion',
        'inbound' => '/xui/inbound/get/{id}',
        'installXray' => '/server/installXray/{id}',
        'listInbound' => '/xui/inbound/list',
        'login' => '/login',
        'logs' => '/server/logs',
        'onlines' => '/xui/inbound/onlines',
        'resetClientTraffic' => '/xui/inbound/{id}/resetClientTraffic/{client}',
        'restartPanel' => '/setting/restartPanel',
        'restartXrayService' => '/server/restartXrayService',
        'status' => '/server/status',
        'stopXrayService' => '/server/stopXrayService',
        'updateClient' => '/xui/inbound/updateClient/{id}',
        'updateInbound' => '/xui/inbound/update/{id}',
        'updateSetting' => '/xui/setting/update',
        'updateUser' => '/xui/setting/updateUser',

    ];
    protected $endpointWithId = ['delInbound', 'inbound', 'updateInbound', 'installXray', 'updateClient', 'clientIps', 'clearClientIps', 'api_get', 'api_resetAllClientTraffics', 'api_delDepletedClients', 'api_getClientTraffics','api_getClientTrafficsById'];

    public function updateSetting($webPort, $webCertFile, $webKeyFile, $webBasePath, $xrayTemplateConfig, bool $tgBotEnable = false, $tgExpireDiff = 0, $tgTrafficDiff = 0, $tgCpu = 0, string $tgBotToken = null, $tgBotChatId = null, $tgRunTime = '@daily', $tgBotBackup = false, $timeLocation = 'Asia/Tehran', $webListen = '')
    {
        $com = ['webPort'=>$webPort, 'webCertFile'=>$webCertFile, 'webKeyFile'=>$webKeyFile, 'webBasePath'=>$webBasePath, 'xrayTemplateConfig'=>$xrayTemplateConfig, 'tgBotEnable'=>$tgBotEnable, 'tgExpireDiff'=>$tgExpireDiff, 'tgTrafficDiff'=>$tgTrafficDiff, 'tgCpu'=>$tgCpu, 'tgBotToken'=>$tgBotToken, 'tgBotChatId'=>$tgBotChatId, 'tgRunTime'=>$tgRunTime, 'timeLocation'=>$timeLocation, 'webListen'=>$webListen, 'tgBotBackup'=>$tgBotBackup];
        return $this->curl('updateSetting', $com, true);
    }

    public function addInbound($remark, $port, $protocol, $settings, $streamSettings, $total = 0, $enable = true, $up = 0, $down = 0, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = json_encode($sniffing ?? $this->defaults['sniffing']);
        $sniffing = $this->jsonEncode($sniffing);
        $settings = $this->jsonEncode($settings);
        $streamSettings = $this->jsonEncode($streamSettings);
        $array = [
            'remark' => $remark,
            'port' => $port,
            'protocol' => $protocol,
            'settings' => $settings,
            'streamSettings' => $streamSettings,
            'total' => $total,
            'enable' => $enable,
            'up' => $up,
            'down' => $down,
            'sniffing' => $sniffing,
            'expiryTime' => $expiryTime,
            'listen' => $listen
        ];
        return $this->curl('addInbound', $array, true);
    }


    public function editInbound(bool $enable, string $id, string $remark, int $port, string $protocol,  $settings,  $streamSettings, int $total = 0, int $up = 0, int $down = 0, ?array $sniffing = null, int $expiryTime = 0, string $listen = '')
    {
        $data = [
            'enable' => $enable,
            'remark' => $remark,
            'port' => $port,
            'protocol' => $protocol,
            'settings' => json_encode($settings),
            'streamSettings' => json_encode($streamSettings),
            'total' => $total,
            'up' => $up,
            'down' => $down,
            'sniffing' => json_encode($sniffing ?? $this->defaults['sniffing']),
            'expiryTime' => $expiryTime,
            'listen' => $listen,
        ];

        $this->setId($id);
        return $this->curl('updateInbound', $data, true);
    }


    public function addClient(string $id, array $settings)
    {
        return $this->curl('addClient', ['id' => $id, 'settings' => json_encode($settings)]);
    }

    public function addNewClient($id, $uuid, $email, $subId = '', $tgId = '', $flow = '', $totalgb = 0, $eT = 0, $limitIp = 0, $fingerprint = 'chrome', $isTrojan = false)
    {
        $subId = $subId ?? uniqid();

        $clientData = [
            'id' => $isTrojan ? 'password' : $uuid,
            'enable' => true,
            'flow' => $flow,
            'email' => $email,
            'totalGB' => $totalgb,
            'limitIp' => $limitIp,
            'expiryTime' => $eT,
            'fingerprint' => $fingerprint,
            'tgId' => $tgId,
            'subId' => $subId
        ];

        $settings = ['clients' => [$clientData]];

        return $this->addClient($id, $settings);
    }

    public function editClient($inboundId, string $clientUuid, bool $enableClient, string $email, string $uuid, bool $isTrojan = false, int $totalGB = 0, int $expiryTime = 0, string $tgId = '', string $subId = '', int $limitIp = 0, string $fingerprint = 'chrome', string $flow = '')
    {
        $clientData = [
            'enable' => $enableClient,
            'id' => $isTrojan ? 'password' : $uuid,
            'email' => $email,
            'flow' => $flow,
            'totalGB' => $totalGB,
            'expiryTime' => $expiryTime,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint,
            'tgId' => $tgId,
            'subId' => $subId,
        ];

        return $this->updateClient($inboundId, $clientUuid, ['clients' => [$clientData]]);
    }
    public function editClientByEmail(string $inboundId, string $clientEmail, bool $enableClient, string $email, string $uuid, int $totalGB = 0, int $expiryTime = 0, string $tgId = '', string $subId = '', int $limitIp = 0, string $fingerprint = 'chrome', string $flow = '')
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndexByEmail($settings['clients'], $clientEmail);
        if ($cIndex === false) {
            return false;
        }

        $clientData = [
            'enable' => $enableClient,
            'id' => $inboundData['protocol'] === 'trojan' ? 'password' : 'id',
            'email' => $email,
            'flow' => $flow,
            'totalGB' => $totalGB,
            'expiryTime' => $expiryTime,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint,
            'tgId' => $tgId,
            'subId' => $subId,
        ];

        $settings['clients'][$cIndex] = $clientData;

        return $this->updateClient($inboundId, $clientData[$inboundData['protocol'] === 'trojan' ? 'password' : 'id'], $settings);
    }
    public function enableClient(string $inboundId, string $uuid)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['enable'] = true;

        return $this->updateClient($inboundId, $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'], $settings);
    }


    public function enableClientByEmail(string $inboundId, string $email)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['enable'] = true;

        return $this->updateClient(
            $inboundId,
            $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'],
            $settings
        );
    }

    public function getClientIP($email)
    {
        $this->setId($email);
        return $this->curl('clientIps', true);
    }

    public function clearClientIP($email)
    {
        $this->setId($email);
        return $this->curl('clearClientIps', true);
    }

    public function getClientData($inboundId, string $uuid)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);
        foreach ($settings['clients'] as $client) {
            if ($client['id'] === $uuid) {
                return $client;
            }
        }

        return false;
    }


    public function getClientDataByEmail($inboundId, string $email)
    {
        $inboundData = $this->list(['id' => $inboundId]);
        if (!$inboundData) {
            return false;
        }

        $settings = json_decode($inboundData[0]['settings'], true);

        foreach ($settings['clients'] as $client) {
            if ($client['email'] === $email) {
                return $client;
            }
        }

        return false;
    }


    public function disableClientByEmail($inboundId, string $email)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['enable'] = false;

        return $this->updateClient(
            $inboundId,
            $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'],
            $settings
        );
    }


    public function disableClient($inboundId, string $uuid)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['enable'] = false;

        return $this->updateClient(
            $inboundId,
            $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'],
            $settings
        );
    }


    public function editClientTraffic($inboundId, string $uuid, int $gb)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['totalGB'] = $gb;

        return $this->updateClient(
            $inboundId,
            $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'],
            $settings
        );
    }


    public function editClientTrafficByEmail(string $inboundId, string $email, int $gb)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['totalGB'] = $gb;

        return $this->updateClient(
            $inboundId,
            $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'],
            $settings
        );
    }


    public function resetClientTraffic($id, $client)
    {
        $this->setId($id);
        $this->setClient($client);
        return $this->curl('resetClientTraffic');
    }

    public function resetClientTrafficByUuid(string $id, string $uuid)
    {
        $inboundData = $this->list(['id' => $id])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false) {
            return false;
        }

        $clientEmail = $settings['clients'][$cIndex]['email'];

        $this->setId($id);
        $this->setClient($clientEmail);

        return $this->curl('resetClientTraffic');
    }

    public function delClient($id, $client)
    {
        $this->setId($id);
        $this->setClient($client);
        return $this->curl('delClient');
    }

    public function updateClient($id,  $client, array $settings)
    {
        $this->setId($client);
        return $this->curl('updateClient', ['id' => $id, 'settings' => json_encode($settings)]);
    }


    public function logs()
    {
        return $this->curl('logs');
    }

    public function getListsApi()
    {
        return $this->curl('api_list', [], false);
    }

    public function getApi($id)
    {
        $this->setId($id);
        return $this->curl('api_get', [], false);
    }

    public function resetAllClientTrafficsApi($id)
    {
        $this->setId($id);
        return $this->curl('api_resetAllClientTraffics', []);
    }

    public function delDepletedClientsApi($id)
    {
        $this->setId($id);
        return $this->curl('api_delDepletedClients', []);
    }

    public function delAllDepletedClientsApi()
    {
        $this->setId('-1');
        return $this->curl('api_delDepletedClients', []);
    }

    public function getDb()
    {
        return $this->curl('getDb', []);
    }

    public function getConfigJson()
    {
        return $this->curl('getConfigJson', []);
    }

    public function getClientTraffics($email)
    {
        $this->setId($email);
        return $this->curl('api_getClientTraffics', [], false);
    }

    public function getClientTrafficsById($id)
    {
        $this->setId($id);
        return $this->curl('api_getClientTrafficsById', [], false);
    }

    public function getNewX25519Cert()
    {
        return $this->curl('getNewX25519Cert', []);
    }

    public function removeClient($inboundId, $uuid)
    {
        return $this->delClient($inboundId, $uuid);
    }

    public function removeClientByEmail(string $inboundId, string $email)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false) {
            return false;
        }

        $clientId = $settings['clients'][$cIndex][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'];

        return $this->delClient($inboundId, $clientId);
    }


    public function showOnlines()
    {
        return $this->curl('onlines', []);
    }
    public function createbackup()
    {
        return $this->curl('createbackup', [],false);
    }
}