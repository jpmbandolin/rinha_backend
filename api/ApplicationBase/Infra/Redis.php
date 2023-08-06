<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Environment\Environment;

final class Redis
{
	private static ?\Redis $connection = null;

	private function __construct(){}

    /**
     * @return \Redis
     * @throws \RedisException
     */
	public static function getInternalConnection():\Redis
    {
		if (is_null(self::$connection)) {
			self::$connection = new \Redis;
			self::$connection->connect(Environment::getEnvironment()->getRedis()->getHost());
		}

		return self::$connection;
	}

    /**
     * @param string $key
     * @return mixed
     * @throws \RedisException
     */
	public static function get(string $key):mixed{
		return self::getInternalConnection()->get($key);
	}

    /**
     * @param string $key
     * @param mixed $value
     * @param int|array|null $timeout
     * @return bool
     * @throws \RedisException
     */
	public static function set(string $key, mixed $value, int|array $timeout = null):bool{
		if (!is_string($value)){
			$value = json_encode($value);
		}

		return self::getInternalConnection()->set($key, $value, $timeout);
	}

    /**
     * @param string|int ...$key
     * @return int
     * @throws \RedisException
     */
	public static function del(string|int ...$key):int{
		return self::getInternalConnection()->del(...$key);
	}
}