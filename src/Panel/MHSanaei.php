<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XConvert\XConvert;
use alirezax5\XuiApi\Traits\Additions;

class MHSanaei extends Base
{
    use Additions;

    protected $path = [
        'login' => '/login',
        'status' => '/server/status',
        'restartXrayService' => '/server/restartXrayService',
        'stopXrayService' => '/server/stopXrayService',
        'getXrayVersion' => '/server/getXrayVersion',
        'installXray' => '/server/installXray/{id}',
        'logs' => '/server/logs',
        'restartPanel' => '/setting/restartPanel',
        'allSetting' => '/panel/setting/all',
        'updateSetting' => '/panel/setting/update',
        'updateUser' => '/panel/setting/updateUser',
        'listInbound' => '/panel/inbound/list',
        'inbound' => '/panel/inbound/get/{id}',
        'delInbound' => '/panel/inbound/del/{id}',
        'updateInbound' => '/panel/inbound/update/{id}',
        'addInbound' => '/panel/inbound/add',
        'addClient' => '/panel/inbound/addClient/',
        'delClient' => '/panel/inbound/delClient/{id}',
        'resetClientTraffic' => '/panel/inbound/{id}/resetClientTraffic/{client}',
        'updateClient' => '/panel/inbound/updateClient/{id}',
        'clientIps' => '/panel/inbound/clientIps/{id}',
        'clearClientIps' => '/panel/clearClientIps/{id}',
        'apiMHSanaei_list' => '/panel/API/inbounds/list/',
        'apiMHSanaei_get' => '/panel/API/inbounds/get/{id}',
        'apiMHSanaei_resetAllClientTraffics' => '/panel/API/inbounds/resetAllClientTraffics/{id}',
        'apiMHSanaei_delDepletedClients' => '/panel/API/inbounds/delDepletedClients/{id}',
        'apiMHSanaei_getClientTraffics' => '/panel/API/inbounds/getClientTraffics/{id}',
    ];

    public function updateSetting($webPort, $webCertFile, $webKeyFile, $webBasePath, $xrayTemplateConfig, bool $tgBotEnable = false, $tgExpireDiff = 0, $tgTrafficDiff = 0, $tgCpu = 0, string $tgBotToken = null, $tgBotChatId = null, $tgRunTime = '@daily', $tgBotBackup = false, $timeLocation = 'Asia/Tehran', $webListen = '')
    {
        $com = compact('webPort', 'webCertFile', 'webKeyFile', 'webBasePath', 'xrayTemplateConfig', 'tgBotEnable', 'tgExpireDiff', 'tgTrafficDiff', 'tgCpu', 'tgBotToken', 'tgBotChatId', 'tgRunTime', 'timeLocation', 'webListen', 'tgBotBackup');
        return $this->curl('updateSetting', $com, true);
    }

    public function addInbound($remark, $port, $protocol, $settings, $streamSettings, $total = 0, $up = 0, $down = 0, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = json_encode($sniffing);
        $settings = json_encode($settings);
        $streamSettings = json_encode($streamSettings);
        return $this->curl('addInbound', compact('remark', 'port', 'protocol', 'settings', 'streamSettings', 'total', 'up', 'down', 'sniffing', 'expiryTime', 'listen'), true);
    }

    public function editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total = 0, $up = 0, $down = 0, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = json_encode($sniffing);
        $settings = json_encode($settings);
        $streamSettings = json_encode($streamSettings);
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
            'totalGB' => XConvert::convertFileSize($totalgb, 'GB', 'B'),
            'expiryTime' => $eT,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint
        ]]
        ];

        return $this->addClient($id, $settings);

    }

    public function editClient($inboundId, $clientUuid, $enableClient, $email, $uuid, $totalGB = 0, $expiryTime = 0, $tgId = '', $subId = '', $limitIp = 0, $fingerprint = 'chrome', $flow = '')
    {
        $list = $this->list(['id' => $inboundId])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $clientUuid);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['enable'] = $enableClient;
        $settings['clients'][$cIndex][$idKey] = $uuid;
        $settings['clients'][$cIndex]['flow'] = $flow;
        $settings['clients'][$cIndex]['limitIp'] = $limitIp;
        $settings['clients'][$cIndex]['totalGB'] = XConvert::convertFileSize($totalGB, 'GB', 'B');
        $settings['clients'][$cIndex]['email'] = $email;
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;
        $settings['clients'][$cIndex]['tgId'] = $tgId;
        $settings['clients'][$cIndex]['subId'] = $subId;
        $settings['clients'][$cIndex]['fingerprint'] = $fingerprint;

        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $inboundId, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

    }

    public function editClientByEmail($inboundId, $clientEmail, $enableClient, $email, $uuid, $totalGB = 0, $expiryTime = 0, $tgId = '', $subId = '', $limitIp = 0, $fingerprint = 'chrome', $flow = '')
    {
        $list = $this->list(['id' => $inboundId])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $idKey = $protocol == 'trojan' ? 'password' : 'id';
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $clientEmail);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['enable'] = $enableClient;
        $settings['clients'][$cIndex][$idKey] = $uuid;
        $settings['clients'][$cIndex]['flow'] = $flow;
        $settings['clients'][$cIndex]['limitIp'] = $limitIp;
        $settings['clients'][$cIndex]['totalGB'] = XConvert::convertFileSize($totalGB, 'GB', 'B');
        $settings['clients'][$cIndex]['email'] = $email;
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;
        $settings['clients'][$cIndex]['tgId'] = $tgId;
        $settings['clients'][$cIndex]['subId'] = $subId;
        $settings['clients'][$cIndex]['fingerprint'] = $fingerprint;

        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $inboundId, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }

    public function enableClient($id, $uuid)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['enable'] = true;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];


        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

    }

    public function enableClientByEmail($id, $email)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['enable'] = true;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

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

    public function disableClientByEmail($id, $email)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['enable'] = false;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

    }

    public function disableClient($id, $uuid)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['enable'] = false;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];


        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

    }

    public function editClientTraffic($id, $uuid, $gb)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['totalGB'] = XConvert::convertFileSize($gb, 'GB', 'B');
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];


        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

    }


    public function editClientTrafficByEmail($id, $email, $gb)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['totalGB'] = XConvert::convertFileSize($gb, 'GB', 'B');
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }

    public function resetClientTraffic($id, $client)
    {
        $this->setId($id);
        $this->setClient($client);
        return $this->curl('resetClientTraffic');
    }

    public function delClient($id, $client)
    {
        $this->setId($client);
        return $this->curl('delClient', compact('id'));
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

    public function getClientTraffics($email)
    {
        $this->setId($email);
        return $this->curl('apiMHSanaei_getClientTraffics', []);
    }
}