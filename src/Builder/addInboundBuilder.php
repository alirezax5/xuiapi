<?php
namespace alirezax5\XuiApi\Builder;

class addInboundBuilder
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


    public function setUp(int $up): self
    {
        $this->up = $up;
        return $this;
    }

    public function setDown(int $down): self
    {
        $this->down = $down;
        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function setRemark(string $remark): self
    {
        $this->remark = $remark;
        return $this;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;
        return $this;
    }

    public function setExpiryTime(int $expiryTime): self
    {
        $this->expiryTime = $expiryTime;
        return $this;
    }

    public function setListen(?string $listen): self
    {
        $this->listen = $listen;
        return $this;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;
        return $this;
    }

    public function setSettings( $settings): self
    {
        $this->settings = $settings;
        return $this;
    }

    public function setStreamSettings( $streamSettings): self
    {
        $this->streamSettings = $streamSettings;
        return $this;
    }

    public function setSniffing( $sniffing): self
    {
        $this->sniffing = $sniffing;
        return $this;
    }

    public function setAllocate($allocate): self
    {
        $this->allocate = $allocate;
        return $this;
    }
    public function build()
    {
        return get_object_vars($this);
    }
}