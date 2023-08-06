<?php

namespace ApplicationBase\Infra\Environment;

class Migrations
{
    public function __construct(private readonly string $dbHost){

    }

    /**
     * @return string
     */
    public function getDbHost(): string{
        return $this->dbHost;
    }
}