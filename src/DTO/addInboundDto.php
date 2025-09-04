<?php

namespace alirezax5\XuiApi\DTO;

class addInboundDto
{
    public int $up = 0;
    public int $down = 0;
    public int $total = 0;
    public string $remark;
    public bool $enable;
    public int $expiryTime;
    public $listen = null;
    public int $port;
    public string $protocol;
    public $settings;
    public $streamSettings;
    public $sniffing;
    public $allocate;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}