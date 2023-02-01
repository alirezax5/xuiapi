<?php

namespace alirezax5\XuiApi\Panel;

class Vaxilu extends Base
{

    public function updateSetting($webPort, $webCertFile, $webKeyFile, $webBasePath, $xrayTemplateConfig, $timeLocation = 'Asia/Tehran', $webListen = '')
    {
        $com = compact('webPort', 'webCertFile', 'webKeyFile', 'webBasePath', 'xrayTemplateConfig', 'timeLocation', 'webListen');
        return $this->curl('updateSetting', $com, true);
    }

    public function addInbound($remark, $port, $protocol, $settings, $streamSettings, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = json_encode($sniffing);
        $settings = json_encode($settings);
        $streamSettings = json_encode($streamSettings);
        return $this->curl('addInbound', compact('remark', 'port', 'protocol', 'settings', 'streamSettings', 'sniffing', 'expiryTime', 'listen'), true);
    }

    public function editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = json_encode($sniffing);
        $settings = json_encode($settings);
        $streamSettings = json_encode($streamSettings);
        $this->setId($id);
        return $this->curl('updateInbound', compact('enable', 'remark', 'port', 'protocol', 'settings', 'streamSettings', 'sniffing', 'expiryTime', 'listen'), true);
    }
}