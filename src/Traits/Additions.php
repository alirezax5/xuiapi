<?php

namespace alirezax5\XuiApi\Traits;

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

        return array_filter($list['obj'], fn($item) => $this->doesItemMatchFilter(
            $item,
            json_decode($item['settings'], true) ?? [],
            $filter
        ));
    }

    private function doesItemMatchFilter(array $item, array $settings, array $filter)
    {
        return (!isset($filter['id']) || $filter['id'] === (int)$item['id'])
            && (!isset($filter['port']) || $filter['port'] === (int)$item['port'])
            && (!isset($filter['uid']) || $this->checkExistsUuidOnClients($settings['clients'] ?? [], $filter['uid']))
            && (!isset($filter['protocol']) || $filter['protocol'] === $item['protocol']);
    }


    public function checkExistsUuidOnClients(array $clients, string $uid)
    {
        foreach ($clients as $client) {
            if ($uid === ($client['password'] ?? $client['id'] ?? '')) {
                return true;
            }
        }
        return false;
    }


    private function getClientIndexBy(array $clients, string $identifier, string $key = 'id')
    {
        foreach ($clients as $index => $client) {
            $compare = $key === 'id' ? ($client['password'] ?? $client['id'] ?? '') : $client[$key];
            if ($identifier === $compare) {
                return $index;
            }
        }
        return false;
    }


    private function updateClientSettings(int $inboundId, string $identifier, string $key, array $updates)
    {
        $inboundData = $this->list(['id' => $inboundId])[0] ?? null;
        if (!$inboundData) {
            return false;
        }

        $settings = json_decode($inboundData['settings'], true) ?? [];
        $index = $this->getClientIndexBy($settings['clients'] ?? [], $identifier, $key);

        if ($index === false) {
            return false;
        }

        foreach ($updates as $field => $value) {
            $settings['clients'][$index][$field] = $value;
        }

        $clientId = $settings['clients'][$index][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'] ?? '';

        return $this->updateClient($clientId, [
            'id' => $inboundId,
            'settings' => json_encode($settings)
        ]);
    }


    public function removeClientByEmail(int $inboundId, string $email)
    {
        $inboundData = $this->list(['id' => $inboundId])[0] ?? null;
        if (!$inboundData) {
            return false;
        }

        $settings = json_decode($inboundData['settings'], true) ?? [];
        $index = $this->getClientIndexBy($settings['clients'] ?? [], $email, 'email');

        if ($index === false) {
            return false;
        }

        $clientId = $settings['clients'][$index][$inboundData['protocol'] === 'trojan' ? 'password' : 'id'] ?? '';

        return $this->delClient($inboundId, $clientId);
    }


    public function resetClientTrafficByUuid(int $inboundId, string $uuid)
    {
        $inboundData = $this->list(['id' => $inboundId])[0] ?? null;
        if (!$inboundData) {
            return false;
        }

        $settings = json_decode($inboundData['settings'], true) ?? [];
        $index = $this->getClientIndexBy($settings['clients'] ?? [], $uuid);

        if ($index === false) {
            return false;
        }

        return $this->resetClientTraffic($inboundId, $settings['clients'][$index]['email'] ?? '');
    }


    public function editClientTrafficByEmail(int $inboundId, string $email, int $gb)
    {
        return $this->updateClientSettings($inboundId, $email, 'email', ['totalGB' => $gb]);
    }


    public function editClientTraffic(int $inboundId, string $uuid, int $gb)
    {
        return $this->updateClientSettings($inboundId, $uuid, 'id', ['totalGB' => $gb]);
    }


    public function editClientExpiryTime(int $inboundId, string $uuid, int $expiryTime)
    {
        return $this->updateClientSettings($inboundId, $uuid, 'id', ['expiryTime' => $expiryTime]);
    }


    public function editClientExpiryTimeByEmail(int $inboundId, string $email, int $expiryTime)
    {
        return $this->updateClientSettings($inboundId, $email, 'email', ['expiryTime' => $expiryTime]);
    }
}