<?php

namespace ApplicationBase\Infra\Environment;

class Environment
{
    private static Environment $environment;
    private function __construct(
        private readonly Database $database,
        private readonly Migrations $migrations,
        private readonly Redis $redis,
        private readonly Slim $slim
    )
    {

    }

    /**
     * This method should be called once.
     *
     * @param Database $database
     * @param Migrations $migrations
     * @param Redis $redis
     * @param Slim $slim
     * @return void
     */
    public static function setupEnvironment(
        Database $database,
        Migrations $migrations,
        Redis $redis,
        Slim $slim
    ): void {
        if (!isset(self::$environment)){
            self::$environment = new self($database, $migrations, $redis, $slim);
        }
    }

    /**
     * self::setupEnvironment should be called before calling this method.
     *
     * @return static
     */
    public static function getEnvironment(): self
    {
        return self::$environment;
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * @return Migrations
     */
    public function getMigrations(): Migrations
    {
        return $this->migrations;
    }

    /**
     * @return Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

    /**
     * @return Slim
     */
    public function getSlim(): Slim
    {
        return $this->slim;
    }
}