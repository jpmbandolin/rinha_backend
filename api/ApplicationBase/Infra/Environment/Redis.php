<?php

namespace ApplicationBase\Infra\Environment;

class Redis
{
    public function __construct(
        private readonly string $host
    )
    {

    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }
}