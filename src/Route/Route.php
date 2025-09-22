<?php

namespace Alirezax5\XuiApi\Route;

use InvalidArgumentException;

class Route
{
    protected array $staticRoutes = [];
    protected array $dynamicRoutes = [];
    protected const ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    public function __construct()
    {
        // مسیرهای پیش‌فرض static
        $this->staticRoutes = [
            'login' => ['path' => '/login', 'method' => 'POST'],
            'status' => ['path' => '/panel/api/server/status', 'method' => 'GET'],
            'getConfigJson' => ['path' => '/panel/api/server/getConfigJson', 'method' => 'POST'],
            'getDb' => ['path' => '/panel/api/server/getDb', 'method' => 'POST'],
            'restartXrayService' => ['path' => '/server/restartXrayService', 'method' => 'POST'],
            'stopXrayService' => ['path' => '/server/stopXrayService', 'method' => 'POST'],
            'getXrayVersion' => ['path' => '/panel/api/server/getXrayVersion', 'method' => 'GET'],
            'installXray' => ['path' => '/server/installXray/{id}', 'method' => 'POST'],
            'allSetting' => ['path' => '/xui/setting/all', 'method' => 'POST'],
            'updateSetting' => ['path' => '/xui/setting/update', 'method' => 'POST'],
            'updateUser' => ['path' => '/xui/setting/updateUser', 'method' => 'POST'],
            'listInbound' => ['path' => '/panel/api/inbounds/list', 'method' => 'GET'],
            'getInbound' => ['path' => '/panel/api/inbounds/get/{id}', 'method' => 'GET'],
            'getClientTraffics' => ['path' => '/panel/api/inbounds/getClientTraffics/{email}', 'method' => 'GET'],
            'getClientTrafficsById' => ['path' => '/panel/api/inbounds/getClientTrafficsById/{id}', 'method' => 'GET'],
            'createbackup' => ['path' => '/panel/api/inbounds/createbackup', 'method' => 'GET'],
            'addInbound' => ['path' => '/panel/api/inbounds/add', 'method' => 'POST'],
            'delInbound' => ['path' => '/panel/api/inbounds/del/{id}', 'method' => 'POST'],
            'updateInbound' => ['path' => '/panel/api/inbounds/update/{id}', 'method' => 'POST'],
            'clientIps' => ['path' => '/panel/api/inbounds/clientIps/{email}', 'method' => 'POST'],
            'clearClientIps' => ['path' => '/panel/api/inbounds/clearClientIps/{email}', 'method' => 'POST'],
            'addClient' => ['path' => '/panel/api/inbounds/addClient', 'method' => 'POST'],
            'delClient' => ['path' => '/panel/api/inbounds/{id}/delClient/{clientId}', 'method' => 'POST'],
            'updateClient' => ['path' => '/panel/api/inbounds/updateClient/{clientId}', 'method' => 'POST'],
            'resetClientTraffic' => ['path' => '/panel/api/inbounds/{id}/resetClientTraffic/{email}', 'method' => 'POST'],
            'resetAllTraffics' => ['path' => '/panel/api/inbounds/resetAllTraffics', 'method' => 'POST'],
            'resetAllClientTraffics' => ['path' => '/panel/api/inbounds/resetAllClientTraffics/{id}', 'method' => 'POST'],
            'delDepletedClients' => ['path' => '/panel/api/inbounds/delDepletedClients/{id}', 'method' => 'POST'],
            'onlines' => ['path' => '/panel/api/inbounds/onlines', 'method' => 'POST'],
            'backuptotgbot' => ['path' => '/panel/api/backuptotgbot', 'method' => 'GET'],
            'getNewUUID' => ['path' => '/panel/api/server/getNewUUID', 'method' => 'GET'],
            'getNewX25519Cert' => ['path' => '/panel/api/server/getNewX25519Cert', 'method' => 'GET'],
            'getNewmldsa65' => ['path' => '/panel/api/server/getNewmldsa65', 'method' => 'GET'],
            'getNewmlkem768' => ['path' => '/panel/api/server/getNewmlkem768', 'method' => 'GET'],
            'getNewVlessEnc' => ['path' => '/panel/api/server/getNewVlessEnc', 'method' => 'GET'],
            'stopXrayServicemh' => ['path' => '/panel/api/server/stopXrayService', 'method' => 'POST'],
            'restartXrayServicemh' => ['path' => '/panel/api/server/restartXrayService', 'method' => 'POST'],
            'installXraymh' => ['path' => '/panel/api/server/installXray/{version}', 'method' => 'POST'],
            'updateGeofile' => ['path' => '/panel/api/server/updateGeofile', 'method' => 'POST'],
            'updateGeofileFilename' => ['path' => '/panel/api/server/updateGeofile/{fileName}', 'method' => 'POST'],
            'logs' => ['path' => '/panel/api/server/logs/{count}', 'method' => 'POST'],
            'xraylogs' => ['path' => '/panel/api/server/xraylogs/{count}', 'method' => 'POST'],
            'xraylogsgetNewEchCert' => ['path' => '/panel/api/server/getNewEchCert', 'method' => 'POST'],
            'lastOnline' => ['path' => '/panel/api/inbounds/lastOnline', 'method' => 'POST'],
            'updateClientTraffic' => ['path' => '/panel/api/inbounds/updateClientTraffic/{email}', 'method' => 'POST'],
            'delClientByEmail' => ['path' => '/panel/api/inbounds/{id}/delClientByEmail/{email}', 'method' => 'POST'],
        ];
    }

    public function addRoute(string $name, string $path, string $method = 'POST'): self
    {
        $method = strtoupper($method);
        if (!in_array($method, self::ALLOWED_METHODS, true)) {
            throw new InvalidArgumentException("Invalid HTTP method: $method");
        }
        $this->dynamicRoutes[$name] = ['path' => $path, 'method' => $method];
        return $this;
    }

    public function getRoute(string $name, array $params = []): array
    {
        $route = $this->staticRoutes[$name] ?? $this->dynamicRoutes[$name] ?? null;

        if (!$route) {
            throw new InvalidArgumentException("Route '$name' not found.");
        }

        $path = $route['path'];

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $path = str_replace("{" . $key . "}", $value, $path);
            }
        }

        return [
            'path' => $path,
            'method' => $route['method']
        ];
    }

    public function needsParams(string $name): bool
    {
        $route = $this->staticRoutes[$name] ?? $this->dynamicRoutes[$name] ?? null;

        if (!$route) {
            throw new InvalidArgumentException("Route '$name' not found.");
        }

        return strpos($route['path'], '{') !== false;
    }

    public function listRoutes(): array
    {
        return [
            'static' => $this->staticRoutes,
            'dynamic' => $this->dynamicRoutes,
        ];
    }
}