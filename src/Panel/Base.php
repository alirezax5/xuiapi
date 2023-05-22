<?php

namespace alirezax5\XuiApi\Panel;


class Base
{
    protected $url, $username, $password, $id, $cookie, $client;
    protected $path = [
        'login' => '/login',
        'status' => '/server/status',
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
        'apiMHSanaei_list' => '/xui/API/inbounds/list/',
        'apiMHSanaei_get' => '/xui/API/inbounds/get/{id}',
        'apiMHSanaei_resetAllClientTraffics' => '/xui/API/inbounds/resetAllClientTraffics/{id}',
        'apiMHSanaei_delDepletedClients' => '/xui/API/inbounds/delDepletedClients/{id}',
        'apiMHSanaei_getClientTraffics' => '/xui/API/inbounds/getClientTraffics/{id}',
    ];
    protected $defaults = [
        'sniffing' => [
            "enabled" => true,
            "destOverride" => [
                "http",
                "tls"
            ]
        ],
    ];

    /**
     * Calculates sum of squares of an array
     *
     * Loops over each element in the array, squares it, and adds it to
     * total. Returns total.
     *
     * This function can also be implemented using array_reduce();
     *
     * @access public
     * @param array $url
     */
    public function __construct($url, $username, $password)
    {
        $this->url = $url;
        $this->cookie = __DIR__ . DIRECTORY_SEPARATOR . "cookie.txt";
        $this->username = $username;
        $this->password = $password;
    }

    protected function curl($path, $body = [], $isPost = true)
    {
        $ch = curl_init();
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->getUrl($path),
            CURLOPT_POST => $isPost == true ? true : false,
            CURLOPT_CUSTOMREQUEST => $isPost == true ? 'POST' : 'GET',
            CURLOPT_POSTFIELDS => http_build_query($body),
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEFILE => $this->getCookie(),
            CURLOPT_COOKIEJAR => $this->getCookie(),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];
        curl_setopt_array($ch, $options);
        $StatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = curl_exec($ch);
        return json_decode($res, true);
    }

    public function jsonEncode($json)
    {
        return json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    protected function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    protected function getId()
    {
        return $this->id;
    }

    protected function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    protected function getClient()
    {
        return $this->client;
    }

    protected function getUrl($path): string
    {

        if (isset($this->path[$path])) {
            $urlPath = $this->path[$path];
            $arrPath = ['delInbound', 'inbound', 'updateInbound', 'installXray', 'delClient', 'clientIps', 'clearClientIps', 'apiMHSanaei_get', 'apiMHSanaei_resetAllClientTraffics', 'apiMHSanaei_delDepletedClients', 'apiMHSanaei_getClientTraffics'];
            $arrPathWithClient = ['resetClientTraffic'];
            if (in_array($path, $arrPath)) {
                $urlPath = strtr($this->path[$path], ['{id}' => $this->getId()]);
            }
            if (in_array($path, $arrPathWithClient)) {
                $urlPath = strtr($this->path[$path], ['{id}' => $this->getId(), '{client}' => $this->getClient()]);
            }
            return $this->url . $urlPath;
        }

        return $this->url;
    }

    public function setCookie($dir)
    {
        $this->cookie = $dir;
        return $this;
    }

    public function getCookie()
    {
        return $this->cookie;
    }

    protected function initCookieFile()
    {
        if (!$this->checkCookieFile())
            file_put_contents($this->cookie, '');

        return $this;
    }

    protected function resetCookieFile()
    {
        unlink($this->cookie);
        $this->initCookieFile();
        return $this;
    }

    protected function checkCookieFile()
    {
        return file_exists($this->cookie);
    }


    protected function auth()
    {
        if (!$this->checkCookieFile()) {
            $this->initCookieFile();
            $this->curl('login', ['username' => $this->username, 'password' => $this->password]);
        }
        return $this;
    }

    protected function authForce()
    {
        $this->resetCookieFile();
        $this->curl('login', ['username' => $this->username, 'password' => $this->password]);

        return $this;
    }


    public function login($force = false)
    {
        if ($force == false)
            $this->auth();
        else
            $this->authForce();

        return $this;
    }

    public function status()
    {
        return $this->curl('status', [], true);
    }

    public function getXrayVersion()
    {
        return $this->curl('getXrayVersion', [], true);
    }

    public function restartPanel()
    {
        return $this->curl('restartPanel', [], true);
    }

    public function installXray($version = 'v1.6.4')
    {
        $this->setId($version);
        return $this->curl('installXray', compact('version'), true);
    }

    public function restartXrayService()
    {
        return $this->curl('restartXrayService', [], true);
    }

    public function stopXrayService()
    {
        return $this->curl('stopXrayService', [], true);
    }

    public function startXrayService()
    {
        return $this->curl('restartXrayService', [], true);
    }


    public function listInbound()
    {
        return $this->curl('listInbound', [], true);
    }

    public function allSetting()
    {
        return $this->curl('allSetting', [], true);
    }


    public function updateUser($oldUsername, $oldPassword, $newUsername, $newPassword)
    {
        return $this->curl('updateUser', compact('oldPassword', 'oldUsername', 'newPassword', 'newUsername'), true);
    }

    public function editInboundDataWithKey($id, $key, $value)
    {
        $list = $this->list(['id' => $id])[0];
        if (isset($list[$key]))
            $list[$key] = $value;
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }

    public function removeInbound($id)
    {
        $this->setId($id);
        return $this->curl('delInbound', [], true);
    }

    public function editClientWithKey($inboundId, $clientUuid, $key, $value)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $clientUuid);
        if ($cIndex === false)
            return false;
        if (isset($settings['clients'][$cIndex][$key]))
            $settings['clients'][$cIndex][$key] = $value;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $inboundId, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }

    public function editClientByEmailWithKey($inboundId, $clientEmail, $key, $value)
    {
        $list = $this->list(['id' => $inboundId])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $clientEmail);
        if ($cIndex === false)
            return false;
        if (isset($settings['clients'][$cIndex][$key]))
            $settings['clients'][$cIndex][$key] = $value;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $inboundId, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }

}