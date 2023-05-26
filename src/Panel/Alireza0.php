<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XuiApi\Traits\Additions;

class Alireza0 extends Base
{
    use Additions;

    public function updateSetting($webPort, $webCertFile, $webKeyFile, $webBasePath, $xrayTemplateConfig, bool $tgBotEnable = false, $tgExpireDiff = 0, $tgTrafficDiff = 0, $tgCpu = 0, string $tgBotToken = null, $tgBotChatId = null, $tgRunTime = '@daily', $tgBotBackup = false, $timeLocation = 'Asia/Tehran', $webListen = '')
    {
        $com = compact('webPort', 'webCertFile', 'webKeyFile', 'webBasePath', 'xrayTemplateConfig', 'tgBotEnable', 'tgExpireDiff', 'tgTrafficDiff', 'tgCpu', 'tgBotToken', 'tgBotChatId', 'tgRunTime', 'timeLocation', 'webListen', 'tgBotBackup');
        return $this->curl('updateSetting', $com, true);
    }

    public function addInbound($remark, $port, $protocol, $settings, $streamSettings, $total = 0, $up = 0, $down = 0, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = $this->jsonEncode($sniffing);
        $settings = $this->jsonEncode($settings);
        $streamSettings = $this->jsonEncode($streamSettings);
        return $this->curl('addInbound', compact('remark', 'port', 'protocol', 'settings', 'streamSettings', 'total', 'up', 'down', 'sniffing', 'expiryTime', 'listen'), true);
    }

    public function editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total = 0, $up = 0, $down = 0, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = $this->jsonEncode($sniffing);
        $settings = $this->jsonEncode($settings);
        $streamSettings = $this->jsonEncode($streamSettings);
        $this->setId($id);
        return $this->curl('updateInbound', compact('enable', 'remark', 'port', 'protocol', 'settings', 'streamSettings', 'total', 'up', 'down', 'sniffing', 'expiryTime', 'listen'), true);
    }


    public function addClient($id, $settings)
    {
        $settings = $this->jsonEncode($settings);
        return $this->curl('addClient', compact('id', 'settings'));
    }

    public function addnewClient($id, $uuid, $email, $flow = '', $totalgb = 0, $eT = 0, $limitIp = 0, $fingerprint = 'chrome', $isTrojan = false)
    {

        $settings = ['clients' => [[
            $isTrojan == true ? 'password' : 'id' => $uuid,
            'email' => $email,
            'flow' => $flow,
            'totalGB' => $totalgb,
            'expiryTime' => $eT,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint
        ]]
        ];

        return $this->addClient($id, $settings);

    }

    public function editClient($inboundId, $clientUuid, bool $enableClient, $email, $uuid, $isTrojan = false, $totalGB = 0, $expiryTime = 0, $tgId = '', $subId = '', $limitIp = 0, $fingerprint = 'chrome', $flow = '')
    {

        $settings = ['clients' => [[
            'enable' => $enableClient,
            $isTrojan == true ? 'password' : 'id' => $uuid,
            'email' => $email,
            'flow' => $flow,
            'totalGB' => $totalGB,
            'expiryTime' => $expiryTime,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint,
            'tgId' => $tgId,
            'subId' => $subId

        ]]
        ];
        return $this->updateClient($inboundId, $clientUuid, $settings);
    }

    public function editClientByEmail($inboundId, $clientEmail, $enableClient, $email, $uuid, $totalGB = 0, $expiryTime = 0, $tgId = '', $subId = '', $limitIp = 0, $fingerprint = 'chrome', $flow = '')
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settingss['clients'], $clientEmail);
        if ($cIndex === false)
            return false;

        $settings = ['clients' => [[
            'enable' => $enableClient,
            $protocol == 'trojan' ? 'password' : 'id' => $uuid,
            'email' => $email,
            'flow' => $flow,
            'totalGB' => $totalGB,
            'expiryTime' => $expiryTime,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint,
            'tgId' => $tgId,
            'subId' => $subId

        ]]
        ];

        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);
    }

    public function enableClient($inboundId, $uuid)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settingss['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings = ['clients' => [[
            'enable' => true,
            $protocol == 'trojan' ? 'password' : 'id' => $uuid,
            'email' => $settingss['clients'][$cIndex]['email'],
            'flow' => $settingss['clients'][$cIndex]['flow'],
            'totalGB' => $settingss['clients'][$cIndex]['totalGB'],
            'expiryTime' => $settingss['clients'][$cIndex]['expiryTime'],
            'limitIp' => $settingss['clients'][$cIndex]['limitIp'],
            'fingerprint' => $settingss['clients'][$cIndex]['fingerprint'],
            'tgId' => $settingss['clients'][$cIndex]['tgId'],
            'subId' => $settingss['clients'][$cIndex]['subId']

        ]]
        ];

        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);
    }

    public function enableClientByEmail($inboundId, $email)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settingss['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings = ['clients' => [[
            'enable' => true,
            $protocol == 'trojan' ? 'password' : 'id' => $settingss['clients'][$cIndex][$idKey],
            'email' => $email,
            'flow' => $settingss['clients'][$cIndex]['flow'],
            'totalGB' => $settingss['clients'][$cIndex]['totalGB'],
            'expiryTime' => $settingss['clients'][$cIndex]['expiryTime'],
            'limitIp' => $settingss['clients'][$cIndex]['limitIp'],
            'fingerprint' => $settingss['clients'][$cIndex]['fingerprint'],
            'tgId' => $settingss['clients'][$cIndex]['tgId'],
            'subId' => $settingss['clients'][$cIndex]['subId']

        ]]
        ];
        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);
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

    public function getClientData($inboundId, $uuid)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false)
            return false;

        return $settings['clients'][$cIndex];
    }

    public function getClientDataByEmail($inboundId, $email)
    {
        $list = $this->list(['id' => $inboundId]);
        if ($list == false) {
            return false;
        }
        $list = $list[0];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false)
            return false;

        return $settings['clients'][$cIndex];
    }

    public function disableClientByEmail($inboundId, $email)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settingss['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings = ['clients' => [[
            'enable' => false,
            $protocol == 'trojan' ? 'password' : 'id' => $settingss['clients'][$cIndex][$idKey],
            'email' => $email,
            'flow' => $settingss['clients'][$cIndex]['flow'],
            'totalGB' => $settingss['clients'][$cIndex]['totalGB'],
            'expiryTime' => $settingss['clients'][$cIndex]['expiryTime'],
            'limitIp' => $settingss['clients'][$cIndex]['limitIp'],
            'fingerprint' => $settingss['clients'][$cIndex]['fingerprint'],
            'tgId' => $settingss['clients'][$cIndex]['tgId'],
            'subId' => $settingss['clients'][$cIndex]['subId']

        ]]
        ];

        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);
    }

    public function disableClient($inboundId, $uuid)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settingss['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings = ['clients' => [[
            'enable' => false,
            $protocol == 'trojan' ? 'password' : 'id' => $uuid,
            'email' => $settingss['clients'][$cIndex]['email'],
            'flow' => $settingss['clients'][$cIndex]['flow'],
            'totalGB' => $settingss['clients'][$cIndex]['totalGB'],
            'expiryTime' => $settingss['clients'][$cIndex]['expiryTime'],
            'limitIp' => $settingss['clients'][$cIndex]['limitIp'],
            'fingerprint' => $settingss['clients'][$cIndex]['fingerprint'],
            'tgId' => $settingss['clients'][$cIndex]['tgId'],
            'subId' => $settingss['clients'][$cIndex]['subId']

        ]]
        ];

        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);

    }

    public function editClientTraffic($inboundId, $uuid, $gb)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settingss['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings = ['clients' => [[
            'enable' => $settingss['clients'][$cIndex]['enable'],
            $protocol == 'trojan' ? 'password' : 'id' => $uuid,
            'email' => $settingss['clients'][$cIndex]['email'],
            'flow' => $settingss['clients'][$cIndex]['flow'],
            'totalGB' => $gb,
            'expiryTime' => $settingss['clients'][$cIndex]['expiryTime'],
            'limitIp' => $settingss['clients'][$cIndex]['limitIp'],
            'fingerprint' => $settingss['clients'][$cIndex]['fingerprint'],
            'tgId' => $settingss['clients'][$cIndex]['tgId'],
            'subId' => $settingss['clients'][$cIndex]['subId']

        ]]
        ];

        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);
    }


    public function editClientTrafficByEmail($inboundId, $email, $gb)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settingss['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings = ['clients' => [[
            'enable' => true,
            $protocol == 'trojan' ? 'password' : 'id' => $settingss['clients'][$cIndex][$idKey],
            'email' => $email,
            'flow' => $settingss['clients'][$cIndex]['flow'],
            'totalGB' => $gb,
            'expiryTime' => $settingss['clients'][$cIndex]['expiryTime'],
            'limitIp' => $settingss['clients'][$cIndex]['limitIp'],
            'fingerprint' => $settingss['clients'][$cIndex]['fingerprint'],
            'tgId' => $settingss['clients'][$cIndex]['tgId'],
            'subId' => $settingss['clients'][$cIndex]['subId']

        ]]
        ];
        return $this->updateClient($inboundId, $settingss['clients'][$cIndex][$idKey], $settings);
    }

    public function resetClientTraffic($id, $client)
    {
        $this->setId($id);
        $this->setClient($client);
        return $this->curl('resetClientTraffic');
    }

    public function delClient($id, $client)
    {
        $this->setId($id);
        $this->setClient($client);
        return $this->curl('delClient');
    }

    public function updateClient($id, $client, $settings)
    {
        $this->setId($client);
        $settings = $this->jsonEncode($settings);
        return $this->curl('updateClient', compact('id', 'settings'));
    }

    public function logs()
    {
        return $this->curl('logs');
    }

    public function getListsApi()
    {
        return $this->curl('apiMHSanaei_list', [], false);
    }

    public function getApi($id)
    {
        $this->setId($id);
        return $this->curl('apiMHSanaei_get', [], false);
    }

    public function resetAllClientTrafficsApi($id)
    {
        $this->setId($id);
        return $this->curl('apiMHSanaei_resetAllClientTraffics', []);
    }

    public function delDepletedClientsApi($id)
    {
        $this->setId($id);
        return $this->curl('apiMHSanaei_delDepletedClients', []);
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
        return $this->curl('apiMHSanaei_getClientTraffics', []);
    }

    public function getNewX25519Cert($email)
    {
        return $this->curl('getNewX25519Cert', []);
    }

    public function removeClient($inboundId, $uuid)
    {
        return $this->delClient($inboundId, $uuid);
    }

    public function removeClientByEmail($inboundId, $email)
    {

        $list = $this->list(['id' => $inboundId])[0];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settingss = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settingss['clients'], $email);
        if ($cIndex === false)
            return false;

        return $this->delClient($inboundId, $settingss['clients'][$cIndex][$idKey]);
    }
}