<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XConvert\XConvert;
use alirezax5\XuiApi\Traits\Additions;

class NidukaAkalanka extends Base
{
    use Additions;

    public function updateSetting($webPort, $webCertFile, $webKeyFile, $webBasePath, $xrayTemplateConfig, bool $tgBotEnable = false, $tgNotifyExpireTimeDiff = 0, $tgNotifyTrafficDiff = 0, string $tgBotToken = null, $tgBotChatId = null, $tgRunTime = null, $timeLocation = 'Asia/Tehran', $webListen = '')
    {
        return $this->curl('updateSetting', compact('webPort', 'webCertFile', 'webKeyFile', 'webBasePath', 'xrayTemplateConfig', 'tgBotEnable', 'tgNotifyExpireTimeDiff', 'tgNotifyTrafficDiff', 'tgBotToken', 'tgBotChatId', 'tgRunTime', 'timeLocation', 'webListen'), true);
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

    public function editClient($inboundId, $clientUuid, $enableClient, $email, $uuid, $totalGB = 0, $expiryTime = 0, $limitIp = 0, $fingerprint = 'chrome', $flow = '')
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
        $settings['clients'][$cIndex]['totalGB'] = XConvert::convertFileSize($totalGB, 'GB', 'B');
        $settings['clients'][$cIndex]['email'] = $email;
        $settings['clients'][$cIndex]['limitIp'] = $limitIp;
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;
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

    public function editClientByEmail($inboundId, $clientEmail, $enableClient, $email, $uuid, $totalGB = 0, $expiryTime = 0, $limitIp = 0, $fingerprint = 'chrome', $flow = '')
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
        $settings['clients'][$cIndex]['totalGB'] = XConvert::convertFileSize($totalGB, 'GB', 'B');
        $settings['clients'][$cIndex]['email'] = $email;
        $settings['clients'][$cIndex]['limitIp'] = $limitIp;
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;
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
}