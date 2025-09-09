<?php

namespace alirezax5\XuiApi\Panel;


use alirezax5\XuiApi\Route\Route;
use alirezax5\XuiApi\XuiApiException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\FileCookieJar;
use Psr\Http\Client\ClientInterface;

abstract class Base
{
    protected string $url;
    protected string $username;
    protected string $password;
    protected ?string $id = null;
    protected Route $route;
    protected ?string $clientIdentifier = null;
    protected ClientInterface $httpClient;
    protected FileCookieJar $cookieJar;
    protected string $cookieFilePath;

    public function __construct(string $url, string $username, string $password, ?ClientInterface $httpClient = null, ?string $cookieFilePath = null)
    {
        $this->url = rtrim($url, '/');
        $this->username = $username;
        $this->password = $password;
        $this->httpClient = $httpClient ?? new GuzzleClient(['verify' => false]);

        // Set default cookie file path to project root
        $this->cookieFilePath = $cookieFilePath ?? dirname(__DIR__, 2) . '/xui_cookies.json';

        // Check if the directory is writable
        $cookieDir = dirname($this->cookieFilePath);
        if (!is_dir($cookieDir) || !is_writable($cookieDir)) {
            throw new XuiApiException("Cookie file directory '$cookieDir' is not writable.");
        }
        $this->cookieJar = new FileCookieJar($this->cookieFilePath, true);
        $this->route = new Route();
    }

    protected function request(string $pathKey, array $body = [], array $params = []): array
    {
        $route = $this->route->getRoute($pathKey, $params);
        $url = $this->url . $route['path'];
        $options = [
            'cookies' => $this->cookieJar,
            'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'],
        ];

        if ($route['method'] === 'POST') {
            $options['form_params'] = $body;
        }

        try {
            $response = $this->httpClient->request($route['method'], $url, $options);
            if ($response->getStatusCode() !== 200) {
                throw new XuiApiException('HTTP error: ' . $response->getStatusCode());
            }
            $data = json_decode($response->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new XuiApiException('JSON decode error: ' . json_last_error_msg());
            }
            return $data;
        } catch (\Throwable $e) {
            throw new XuiApiException('Request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function login(bool $force = false): void
    {
        $this->cookieJar = new FileCookieJar($this->cookieFilePath, $force);

        if (!$force) {
            try {
                $testResponse = $this->request('status');
                if (isset($testResponse['success']) && $testResponse['success']) {
                    return; 
                }
            } catch (XuiApiException $e) {
            }
        }

        try {
            $response = $this->request('login', [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            if (!isset($response['success']) || !$response['success']) {
                throw new XuiApiException('Login failed: Invalid response');
            }

            // Save new cookies to the cookie jar
            $this->cookieJar->save($this->cookieFilePath);
        } catch (XuiApiException $e) {
            throw new XuiApiException('Login failed: ' . $e->getMessage(), 0, $e);
        }
    }


    public function listInbound()
    {
        return $this->request('listInbound');
    }

}