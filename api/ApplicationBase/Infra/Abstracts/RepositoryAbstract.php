<?php

namespace ApplicationBase\Infra\Abstracts;

use ApplicationBase\Infra\Database;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\QueryBuilder;

abstract class RepositoryAbstract
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string $className
     * @param string $errorMessage
     * @return array
     * @throws DatabaseException
     */
    protected static function fetchMultiObject(QueryBuilder $queryBuilder, string $className, string $errorMessage): array
    {
        try {
            return Database::getInstance()->fetchMultiObject($queryBuilder, $className) ?: [];
        }catch (\Throwable $t){
            throw new DatabaseException($errorMessage, previous: $t);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $className
     * @param string $errorMessage
     * @return mixed
     * @throws DatabaseException
     */
    protected static function fetchObject(QueryBuilder $queryBuilder, string $className, string $errorMessage): mixed
    {
        try {
            return Database::getInstance()->fetchObject($queryBuilder, $className);
        }catch (\Throwable $t){
            throw new DatabaseException($errorMessage, previous: $t);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $errorMessage
     * @return void
     * @throws DatabaseException
     */
    protected static function prepareAndExecute(QueryBuilder $queryBuilder, string $errorMessage): void
    {
        try {
            Database::getInstance()->prepareAndExecute($queryBuilder);
        }catch (\Throwable $t){
            throw new DatabaseException($errorMessage, previous: $t);
        }
    }
}