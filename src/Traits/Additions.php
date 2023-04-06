<?php

namespace alirezax5\XuiApi\Traits;

use alirezax5\XConvert\XConvert;
use inc\functions;

trait Additions
{
    public function list(array $filter = [])
    {
        $list = $this->listInbound();
        if (!isset($list['obj'])) return false;
        if ($filter == []) return $list;
        $result = [];
        foreach ($list['obj'] as $item) {
            $status = true;
            $settings = json_decode($item["settings"], true);

            if (!empty($filter["id"]) && $filter["id"] !== (int)$item['id']) $status = false;
            if (!empty($filter["port"]) && $filter["port"] !== (int)$item['port']) $status = false;
            if (!empty($filter["uid"]) && !$this->checkExistsUuidOnClients($settings['clients'], $filter["uid"])) $status = false;
            if (!empty($filter["protocol"]) && $filter["protocol"] !== $item['protocol']) $status = false;
            if ($status)
                $result[] = $item;
        }
        return $result;
    }

    public function checkExistsUuidOnClients($clients, $uid)
    {
        if (count($clients) == 0)
            return false;
        foreach ($clients as $item) {
            $cuid = $item['password'] ?? $item['id'];
            if ($uid == $cuid)
                return true;
        }
        return false;
    }

    public function getClientIndex($clients, $uid)
    {
        if (count($clients) == 0)
            return false;
        $i = 0;
        foreach ($clients as $item) {
            $cuid = $item['password'] ?? $item['id'];
            if ($uid == $cuid)
                return (int)$i;
            $i++;
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
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];


        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);

    }

    public function editClientExpiryTimeByEmail($id, $email, $expiryTime = 0)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $email);
        if ($cIndex === false)
            return false;
        $settings['clients'][$cIndex]['expiryTime'] = $expiryTime;
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];


        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }



    public function removeClient($id, $uuid)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndex($settings['clients'], $uuid);
        if ($cIndex === false)
            return false;
        unset($settings['clients'][$cIndex]);
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }

    public function removeClientByEmail($id, $Email)
    {
        $list = $this->list(['id' => $id])[0];
        $enable = (bool)$list['enable'];
        $remark = $list['remark'];
        $port = $list['port'];
        $protocol = $list['protocol'];
        $settings = json_decode($list["settings"], true);
        $cIndex = $this->getClientIndexByEmail($settings['clients'], $Email);
        if ($cIndex === false)
            return false;
        unset($settings['clients'][$cIndex]);
        $streamSettings = json_decode($list['streamSettings']);
        $up = $list['up'];
        $down = $list['down'];
        $sniffing = json_decode($list['sniffing']);
        $expiryTime = $list['expiryTime'];
        $listen = $list['listen'];
        $total = $list['total'];
        return $this->editInbound($enable, $id, $remark, $port, $protocol, $settings, $streamSettings, $total, $up, $down, $sniffing, $expiryTime, $listen);
    }
}