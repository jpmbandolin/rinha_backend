<?php

namespace Modules\People\Domain;

use Modules\People\Infra\PersonRepository;
use ApplicationBase\Infra\Exceptions\DatabaseException;

trait Actions
{
	/**
	 * @param string $id
	 *
	 * @return null|Person
	 * @throws DatabaseException
	 */
	public static function getById(string $id): ?Person
	{
		return PersonRepository::getById($id);
	}
	
	/**
	 * @param null|string $term
	 *
	 * @return Person[]
	 * @throws DatabaseException
	 */
	public static function search(?string $term): array
	{
		return PersonRepository::search($term);
	}
	
	/**
	 * @return int
	 * @throws DatabaseException
	 */
	public static function getPeopleCount(): int
	{
		return PersonRepository::getPeopleCount();
	}
	
	/**
	 * @return Person
	 * @throws DatabaseException
	 */
	public function save(): Person
	{
		PersonRepository::save($this);
		return $this;
	}
}