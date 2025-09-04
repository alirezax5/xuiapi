<?php

namespace alirezax5\XuiApi\Panel;

use alirezax5\XuiApi\Traits\Additions;

class Alireza0 extends Base
{
    use Additions;

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

    public function delInbound(int $id)
    {
        return $this->request('delInbound', [], compact('id'));
    }

    public function updateInbound(int $id)
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
        return $this->request('updateClient', $body, compact($clientId));
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
}