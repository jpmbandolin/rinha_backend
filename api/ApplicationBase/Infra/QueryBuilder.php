<?php

namespace ApplicationBase\Infra;

use Throwable;
use ApplicationBase\Infra\Enums\SqlClauseEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

readonly class QueryBuilder
{
	/**
	 * @param string $sql
	 * @param array  $args
	 */
	public function __construct(
		private string $sql,
		private array  $args = []
	) {}
	
	/**
	 * @param string $sql
	 * @param array  $args
	 * @param bool   $log
	 *
	 * @return QueryBuilder
	 */
	public static function create(string $sql, array $args = [], bool $log = true): QueryBuilder
	{
		return new QueryBuilder(sql: $sql, args: $args);
	}

	/**
	 * @return string
	 */
	public function getSql(): string
	{
		return $this->sql;
	}
	
	/**
	 * @return array
	 */
	public function getArgs(): array
	{
		return $this->args;
	}
}