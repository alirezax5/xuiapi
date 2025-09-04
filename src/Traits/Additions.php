<?php

namespace alirezax5\XuiApi\Traits;


trait Additions
{
    public function list(array $filter = []): array|bool
    {
        $list = $this->listInbound();

        if (empty($list['obj'])) {
            return false;
        }

        if (empty($filter)) {
            return $list;
        }

        return array_filter($list['obj'], function ($item) use ($filter) {
            $settings = json_decode($item['settings'], true);
            return $this->doesItemMatchFilter($item, $settings, $filter);
        });
    }
    private function doesItemMatchFilter(array $item, array $settings, array $filter): bool
    {
        return (!isset($filter['id']) || $filter['id'] === (int) $item['id'])
            && (!isset($filter['port']) || $filter['port'] === (int) $item['port'])
            && (!isset($filter['uid']) || $this->checkExistsUuidOnClients($settings['clients'], $filter['uid']))
            && (!isset($filter['protocol']) || $filter['protocol'] === $item['protocol']);
    }

    public function checkExistsUuidOnClients(array $clients, string $uid): bool
    {
        return array_reduce($clients, fn($carry, $client) => $carry || ($uid === ($client['password'] ?? $client['id'])), false);
    }


    public function getClientIndex(array $clients, string $uid): int|bool
    {
        foreach ($clients as $index => $client) {
            if ($uid === ($client['password'] ?? $client['id'])) {
                return $index;
            }
        }
        return false;
    }

    public function getClientIndexByEmail(array $clients, string $email): int|bool
    {
        foreach ($clients as $index => $item) {
            if ($email === $item['email']) {
                return $index;
            }
        }
        return false;
    }

}