<?php

namespace ApplicationBase\Infra\Environment;

class Database
{
    public function __construct(
        private readonly string $database,
        private readonly string $user,
        private readonly string $password,
        private readonly string $host,
    )
    {

    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }
}