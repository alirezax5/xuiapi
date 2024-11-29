<?php

namespace alirezax5\XuiApi\Traits;

use alirezax5\XConvert\XConvert;
use inc\functions;

trait Additions
{
    public function list(array $filter = [])
    {
        $list = $this->listInbound();
        if (empty($list['obj'])) {
            return false;
        }

        if (empty($filter)) {
            return $list;
        }
        $result = [];
        foreach ($list['obj'] as $item) {
            $settings = json_decode($item['settings'], true);

            if ($this->doesItemMatchFilter($item, $settings, $filter)) {
                $result[] = $item;
            }
        }

        return $result;
    }

    private function doesItemMatchFilter(array $item, array $settings, array $filter): bool
    {
        if (!empty($filter['id']) && $filter['id'] !== (int)$item['id']) {
            return false;
        }

        if (!empty($filter['port']) && $filter['port'] !== (int)$item['port']) {
            return false;
        }

        if (!empty($filter['uid']) && !$this->checkExistsUuidOnClients($settings['clients'], $filter['uid'])) {
            return false;
        }

        if (!empty($filter['protocol']) && $filter['protocol'] !== $item['protocol']) {
            return false;
        }

        return true;
    }

    public function checkExistsUuidOnClients($clients, $uid)
    {
        foreach ($clients as $client) {
            $clientId = $client['password'] ?? $client['id'];
            if ($uid == $clientId) {
                return true;
            }
        }
        return false;
    }


    public function getClientIndex($clients, $uid)
    {
        foreach ($clients as $index => $client) {
            $clientId = $client['password'] ?? $client['id'];
            if ($uid === $clientId) {
                return $index;
            }
        }
        return false;
    }


    public function getClientIndexByEmail($clients, $email)
    {
        if (count($clients) == 0)
            return false;
        $i = 0;
        foreach ($clients as $item) {
            if ($email == $item['email'])
                return (int)$i;
            $i++;
        }
        return false;
    }

    public function editClientExpiryTime($id, $uuid, $expiryTime = 0)
    {
        $list = $this->list(['id' => $id])[0];
        if (!$list) {
            return false;
        }

        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false) {
            return false;
        }
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;

        return $this->editInbound(
            (bool)$list['enable'], // Enable status
            $id,
            $list['remark'],
            $list['port'],
            $list['protocol'],
            $settings,
            json_decode($list['streamSettings']),
            $list['total'],
            $list['up'],
            $list['down'],
            json_decode($list['sniffing']),
            $list['expiryTime'],
            $list['listen']
        );
    }

    public function editClientExpiryTimeByEmail($id, $email, $expiryTime = 0)
    {
        $list = $this->list(['id' => $id])[0];
        if (!$list) {
            return false;
        }

        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false) {
            return false;
        }

        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;

        return $this->editInbound(
            (bool)$list['enable'], // Enable status
            $id,
            $list['remark'],
            $list['port'],
            $list['protocol'],
            $settings,
            json_decode($list['streamSettings']),
            $list['total'],
            $list['up'],
            $list['down'],
            json_decode($list['sniffing']),
            $list['expiryTime'],
            $list['listen']
        );
    }


    public function removeClient($id, $uuid)
    {
        $list = $this->list(['id' => $id])[0];
        if (!$list) {
            return false;
        }

        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false) {
            return false;
        }

        unset($settings['clients'][$cIndex]);

        return $this->editInbound(
            (bool)$list['enable'],
            $id,
            $list['remark'],
            $list['port'],
            $list['protocol'],
            $settings,
            json_decode($list['streamSettings']),
            $list['total'],
            $list['up'],
            $list['down'],
            json_decode($list['sniffing']),
            $list['expiryTime'],
            $list['listen']
        );
    }

    public function removeClientByEmail($id, $email)
    {
        $list = $this->list(['id' => $id])[0];
        if (!$list) {
            return false;
        }

        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false) {
            return false;
        }

        unset($settings['clients'][$cIndex]);

        return $this->editInbound(
            (bool)$list['enable'],
            $id,
            $list['remark'],
            $list['port'],
            $list['protocol'],
            $settings,
            json_decode($list['streamSettings']),
            $list['total'],
            $list['up'],
            $list['down'],
            json_decode($list['sniffing']),
            $list['expiryTime'],
            $list['listen']
        );
    }

}