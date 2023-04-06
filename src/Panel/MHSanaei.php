<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XConvert\XConvert;
use alirezax5\XuiApi\Traits\Additions;

class MHSanaei extends Base
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
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);

        $settings['clients'][] = [
            $isTrojan == true ? 'password' : 'id' => $uuid,
            'email' => $email,
            'flow' => $flow,
            'totalGB' => XConvert::convertFileSize($totalgb, 'GB', 'B'),
            'expiryTime' => $eT,
            'limitIp' => $limitIp,
            'fingerprint' => $fingerprint

        ];
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
        return $this->curl('addClient');
    }

    public function delClient($id, $client, $settings)
    {
        $this->setId($client);
        return $this->curl('delClient', compact('id', 'settings'));
    }

    public function updateClient($id, $client, $settings)
    {
        $this->setId($client);
        return $this->curl('delClient', compact('id', 'settings'));
    }

    public function logs()
    {
        return $this->curl('logs');
    }

}