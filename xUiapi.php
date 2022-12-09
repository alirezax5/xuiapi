<?php

namespace alirezax5\XUiApi;

class xUiapi
{
    private $url, $username, $password, $id;
    private $path = [
        'login' => '/login',
        'inbounds' => '/xui/API/inbounds',
        'inbound' => '/xui/API/inbounds/get/{id}',
        'delInbound' => '/xui/API/inbounds/del/{id}',
        'updateInbound' => '/xui/API/inbounds/update/{id}',
        'addInbound' => '/xui/API/inbounds/add',
    ];
    private $cookie;
    private $defaults = [
        'sniffing' => [
            "enabled" => true,
            "destOverride" => [
                "http",
                "tls"
            ]
        ],
    ];

    public static function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function __construct($url, $username, $password)
    {
        $this->url = $url;
        $this->cookie = __DIR__ . DIRECTORY_SEPARATOR . "cookie.txt";
        $this->username = $username;
        $this->password = $password;
    }

    private function initCookieFile(): xUiapi
    {
        if (!file_exists($this->cookie))
            file_put_contents($this->cookie, '');

        return $this;
    }

    public function setCookie($dir): xUiapi
    {
        $this->cookie = $dir;
        return $this;
    }

    public function getCookie()
    {
        return $this->cookie;
    }

    public function getContentType($path)
    {
        $type = 'application/json';
        if ($path == 'login' || $path == 'inbounds' || $path == 'delInbound')
            $type = 'application/x-www-form-urlencoded';

        return $type;
    }

    public function request($path = null, $body = [], $isPost = true)
    {
        $this->initCookieFile();
        sleep(1);
        $ch = curl_init();
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->getUrl($path),
            CURLOPT_POST => $isPost == true ? true : false,
            CURLOPT_CUSTOMREQUEST => $isPost == true ? 'POST' : 'GET',
            CURLOPT_HTTPHEADER => [
                'Content-Type' => $this->getContentType($path)],
                CURLOPT_POSTFIELDS => http_build_query($body),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_COOKIEFILE => $this->getCookie(),
                CURLOPT_COOKIEJAR => $this->getCookie(),
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
            ];
        curl_setopt_array($ch, $options);
        $StatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = curl_exec($ch);
        return json_decode($res, true);
    }

    private function getUrl($path): string
    {

        if (isset($this->path[$path])) {
            $urlPath = $this->path[$path];
            if ($path == 'inbound' || $path == 'delInbound' || $path == 'updateInbound') {
                $urlPath = strtr($this->path[$path], ['{id}' => $this->getId()]);
            }
            return $this->url . $urlPath;
        }

        return $this->url;
    }

    public function login()
    {
        $username = $this->username;
        $password = $this->password;
        return $this->request('login', compact('username', 'password'));
    }

    public function getInbounds()
    {
        return $this->request('inbounds', [], false);
    }

    public function getInbound($id)
    {
        $this->setId($id);
        return $this->request('inbound', [], false);
    }

    public function addInbound($remark, $port, $protocol, $settings, $streamSettings, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = json_encode($sniffing);
        $settings = json_encode($settings);
        $streamSettings = json_encode($streamSettings);
        return $this->request('addInbound', compact('remark', 'port', 'protocol', 'settings', 'streamSettings', 'sniffing', 'expiryTime', 'listen'), true);
    }

    public function removeInbound($id)
    {
        $this->setId($id);
        return $this->request('delInbound', [], true);
    }

    public function editInbound($id, $remark, $port, $protocol, $settings, $streamSettings, $sniffing = null, $expiryTime = 0, $listen = '')
    {
        $sniffing = $sniffing == null ? $this->defaults['sniffing'] : $sniffing;
        $sniffing = json_encode($sniffing);
        $settings = json_encode($settings);
        $streamSettings = json_encode($streamSettings);
        $this->setId($id);
        return $this->request('updateInbound', compact('remark', 'port', 'protocol', 'settings', 'streamSettings', 'sniffing', 'expiryTime', 'listen'), true);
    }

    private function setId($id): xUiapi
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
