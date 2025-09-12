<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XuiApi\Traits\Additions;

class MHSanaei extends Base
{
    use Additions;

    public function status(): array
    {
        return $this->request('status');
    }

    public function getInbound($id)
    {
        return $this->request('getInbound', [], compact('id'));
    }

    public function getClientTraffics($email)
    {
        return $this->request('getClientTraffics', [], compact('email'));
    }

    public function getClientTrafficsById($id)
    {
        return $this->request('getClientTrafficsById', [], compact('id'));
    }

    public function createbackup()
    {
        return $this->request('createbackup');
    }

    public function addInbound(array $body)
    {
        return $this->request('addInbound', $body);
    }

    public function delInbound( $id)
    {
        return $this->request('delInbound', [], compact('id'));
    }

    public function updateInbound( $id)
    {
        return $this->request('updateInbound', [], compact('id'));
    }

    public function clientIps($email)
    {
        return $this->request('clientIps', [], compact('email'));
    }

    public function clearClientIps($email)
    {
        return $this->request('clearClientIps', [], compact('email'));
    }

    public function addClient(array $body)
    {
        return $this->request('addClient', $body);
    }

    public function delClient($id, $clientId)
    {
        return $this->request('delClient', [], compact('id', 'clientId'));
    }

    public function updateClient($clientId, array $body)
    {
        return $this->request('updateClient', $body, compact('clientId'));
    }

    public function resetClientTraffic($id, $email)
    {
        return $this->request('resetClientTraffic', [], compact('id', 'email'));
    }

    public function resetAllTraffics()
    {
        return $this->request('resetAllTraffics');
    }

    public function resetAllClientTraffics($id)
    {
        return $this->request('resetAllClientTraffics', [], compact('id'));
    }

    public function delDepletedClients($id)
    {
        return $this->request('delDepletedClients', [], compact('id'));
    }

    public function onlines()
    {
        return $this->request('onlines');
    }

    public function backuptotgbot()
    {
        return $this->request('backuptotgbot');
    }

    public function getDb()
    {
        return $this->request('getDb');
    }

    public function getXrayVersion()
    {
        return $this->request('getXrayVersion');
    }

    public function getConfigJson()
    {
        return $this->request('getConfigJson');
    }

    public function getNewUUID()
    {
        return $this->request('getNewUUID');
    }

    public function getNewX25519Cert()
    {
        return $this->request('getNewX25519Cert');
    }

    public function getNewmldsa65()
    {
        return $this->request('getNewmldsa65');
    }

    public function getNewmlkem768()
    {
        return $this->request('getNewmlkem768');
    }

    public function getNewVlessEnc()
    {
        return $this->request('getNewVlessEnc');
    }

    public function stopXrayService()
    {
        return $this->request('stopXrayServicemh');
    }

    public function restartXrayService()
    {
        return $this->request('restartXrayServicemh');
    }

    public function installXray($version)
    {
        return $this->request('installXraymh', [], compact('version'));
    }

    public function updateGeofile()
    {
        return $this->request('updateGeofile');
    }

    public function updateGeofileFilename($fileName)
    {
        return $this->request('updateGeofileFilename', [], compact('fileName'));
    }

    public function logs($count = 50)
    {
        return $this->request('logs', [], compact('count'));
    }

    public function xraylogs($count = 50)
    {
        return $this->request('xraylogs', [], compact('count'));
    }

    public function getNewEchCert($count = 50)
    {
        return $this->request('getNewEchCert', [], compact('count'));
    }
}