<?php

namespace alirezax5\XuiApi\Builder;

class addClientBuilder
{
    public int $id;
    public $settings;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setSettings($settings): self
    {
        $this->settings = $settings;
        return $this;
    }

    public function build()
    {
        return get_object_vars($this);
    }
}