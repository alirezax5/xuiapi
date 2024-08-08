<?php

namespace alirezax5\XuiApi\Panel;


class Base
{
    protected $url, $username, $password, $id, $cookie, $client;
    protected $path = [
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
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->getUrl($path),
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEFILE => $this->getCookie(),
            CURLOPT_COOKIEJAR => $this->getCookie(),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];

        if ($isPost) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_CUSTOMREQUEST] = 'POST';
            $options[CURLOPT_POSTFIELDS] = http_build_query($body);
        } else {
            $options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Error handling
        if ($httpCode !== 200) {
            // Handle error based on HTTP status code
            throw new \Exception('Curl error: HTTP status code ' . $httpCode);
        }

        return json_decode($response, true);
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
        if (!isset($this->path[$path])) {
            return $this->url;
        }

        $urlPath = $this->path[$path];

        if (in_array($path, $this->endpointWithId)) {
            $urlPath = str_replace('{id}', $this->getId(), $urlPath);
        }

        if (in_array($path, $this->endpointWithClient)) {
            $urlPath = str_replace(['{id}', '{client}'], [$this->getId(), $this->getClient()], $urlPath);
        }

        return $this->url . $urlPath;
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
        if ($this->checkCookieFile())
            unlink($this->cookie);

        $this->initCookieFile();
        return $this;
    }

    protected function checkCookieFile()
    {
        return file_exists($this->cookie);
    }

    public function login($force = false)
    {
        if ($force || !$this->checkCookieFile()) {
            $this->resetCookieFile();
            $response = $this->curl('login', ['username' => $this->username, 'password' => $this->password]);

            if (!isset($response['success']) || !$response['success']) {
                // Handle login error gracefully
                throw new \Exception('Login failed');
            }
        }
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

        if (isset($list[$key])) {
            $list[$key] = $value;
            return $this->editInbound(
                (bool)$list['enable'],
                $id,
                $list['remark'],
                $list['port'],
                $list['protocol'],
                json_decode($list['settings'], true),
                json_decode($list['streamSettings']),
                $list['total'],
                $list['up'],
                $list['down'],
                json_decode($list['sniffing']),
                $list['expiryTime'],
                $list['listen']
            );
        }

        return false; // Or throw an exception if the key doesn't exist
    }

    public function removeInbound($id)
    {
        $this->setId($id);
        return $this->curl('delInbound', [], true);
    }

    public function editClientWithKey($inboundId, $clientUuid, $key, $value)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndex($settings['clients'], $clientUuid);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex][$key] = $value;

        return $this->editInbound(
            (bool)$inboundData['enable'],
            $inboundId,
            $inboundData['remark'],
            $inboundData['port'],
            $inboundData['protocol'],
            $settings,
            json_decode($inboundData['streamSettings']),
            $inboundData['total'],
            $inboundData['up'],
            $inboundData['down'],
            json_decode($inboundData['sniffing']),
            $inboundData['expiryTime'],
            $inboundData['listen']
        );
    }

    public function editClientByEmailWithKey($inboundId, $clientEmail, $key, $value)
    {
        $inboundData = $this->list(['id' => $inboundId])[0];
        $settings = json_decode($inboundData['settings'], true);

        $cIndex = $this->getClientIndexByEmail($settings['clients'], $clientEmail);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex][$key] = $value;

        return $this->editInbound(
            (bool)$inboundData['enable'],
            $inboundId,
            $inboundData['remark'],
            $inboundData['port'],
            $inboundData['protocol'],
            $settings,
            json_decode($inboundData['streamSettings']),
            $inboundData['total'],
            $inboundData['up'],
            $inboundData['down'],
            json_decode($inboundData['sniffing']),
            $inboundData['expiryTime'],
            $inboundData['listen']
        );
    }


}