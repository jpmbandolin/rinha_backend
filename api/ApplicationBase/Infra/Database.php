<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Environment\Environment;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use PDOStatement;
use Throwable;

class Database extends \PDO
{
	protected static Database $instance;

	/**
	 * @throws DatabaseException
	 */
	public function __construct(private readonly bool $forceNewInstance = false)
	{
        $databaseEnvironment = Environment::getEnvironment()->getDatabase();

		$host = $databaseEnvironment->getHost();;
		$user = $databaseEnvironment->getUser();
		$pass = $databaseEnvironment->getPassword();
		$database = $databaseEnvironment->getDatabase();

		$dsn = 'mysql:dbname=' . $database .';host=' . $host;

		try{
			parent::__construct($dsn, $user, $pass, ["charset"=>"utf8"]);
			$this->exec("set names utf8");
		}catch(Throwable $t){
			throw new DatabaseException(message: "Error starting database connection", previous: $t);
		}

		if (!isset(self::$instance)){
			self::$instance = $this;
		}
	}

	/**
	 * @return Database
	 */
	public static function getInstance(): Database
	{
		if (!isset(self::$instance) || is_null(self::$instance)){
			self::$instance = new Database;
		}

		return self::$instance;
	}
	
	/**
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return bool|PDOStatement
	 */
	public function prepareAndExecute(QueryBuilder $queryBuilder):bool|PDOStatement{
		$instance = ($this->forceNewInstance ? $this : static::$instance);
		$sql = $instance->prepare($queryBuilder->getSql());
		if(empty($queryBuilder->getArgs())){
			$sql->execute();
		} else{
			$sql->execute($queryBuilder->getArgs());
		}

		return $sql;
	}
	
	/**
	 * @param QueryBuilder $queryBuilder
	 * @param string       $className
	 *
	 * @return array|bool
	 */
	public function fetchMultiObject(QueryBuilder $queryBuilder, string $className = \stdClass::class): array|bool
	{
		$instance = ($this->forceNewInstance ? $this : static::$instance);
		$sql    = $instance->prepareAndExecute($queryBuilder);
		if($className === \stdClass::class) {
			$array = $sql->fetchAll(self::FETCH_CLASS, $className);
		}else{
			$array = $sql->fetchAll(self::FETCH_ASSOC);
			$array = array_map(static function($row) use ($className) {
				return new $className(...$row);
			}, $array);
		}
		$sql->closeCursor();
		return $array;
	}
	
	/**
	 * @param QueryBuilder $queryBuilder
	 * @param string       $className
	 *
	 * @return mixed
	 */
	public function fetchObject(QueryBuilder $queryBuilder, string $className = \stdClass::class): mixed
	{
		$instance = ($this->forceNewInstance ? $this : static::$instance);
		$sql    = $instance->prepareAndExecute($queryBuilder);
		if($className === \stdClass::class){
			$object = $sql->fetch(\PDO::FETCH_OBJ);
		}else{

			$object = $sql->fetch(self::FETCH_ASSOC);
			if($object){
				$object = new $className(...$object);
			}

		}
		$sql->closeCursor();
		return $object;
	}
}