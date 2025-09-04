<?php

namespace alirezax5\XuiApi\DTO;

class updateClient
{
    public int $id;
    public int $settings;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}