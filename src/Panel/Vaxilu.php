<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XuiApi\Traits\Additions;

class Vaxilu extends Base
{
    use Additions;

    public function updateSetting($webPort, $webCertFile, $webKeyFile, $webBasePath, $xrayTemplateConfig, $timeLocation = 'Asia/Tehran', $webListen = '')
    {
        $com = compact('webPort', 'webCertFile', 'webKeyFile', 'webBasePath', 'xrayTemplateConfig', 'timeLocation', 'webListen');
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
}