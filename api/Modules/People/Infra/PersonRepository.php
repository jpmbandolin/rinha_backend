<?php

namespace Modules\People\Infra;

use Modules\People\Domain\Person;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Abstracts\RepositoryAbstract;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class PersonRepository extends RepositoryAbstract
{
	/**
	 * @param string $id
	 *
	 * @return null|Person
	 * @throws DatabaseException
	 */
	public static function getById(string $id): ?Person
	{
		$sql = "SELECT uuid, nickname, name, birthdate, stack
				FROM people
				WHERE uuid = ?";

		return self::fetchObject(
			QueryBuilder::create($sql, [$id]),
			Person::class,
			"Error fetching user by ID"
		) ?: null;
	}
	
	/**
	 * @return Person[]
	 * @throws DatabaseException
	 */
	public static function search(?string $term): array
	{
		$termExists = isset($term);
		$sql = "SELECT uuid, nickname, name, birthdate, stack FROM people ";
		$sql .= $termExists ? "WHERE nickname LIKE ? OR name LIKE ? OR stack LIKE ? " : "";
		$sql .= "LIMIT 50";
		
		$term = "%".$term."%";

		return self::fetchMultiObject(
			QueryBuilder::create($sql, $termExists ? [$term, $term, $term] : []),
			Person::class,
			"Error searching for people"
		);
	}
	
	/**
	 * @return int
	 * @throws DatabaseException
	 */
	public static function getPeopleCount(): int
	{
		$sql = "SELECT COUNT(uuid) AS ammount FROM people";
		
		return (self::fetchObject(
			QueryBuilder::create($sql),
			\stdClass::class,
			"Error counting existing users"
		)->ammount ?? 0);
	}
	
	/**
	 * @param Person $person
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function save(Person $person): void
	{
		$sql = "INSERT INTO people (uuid, nickname, name, birthdate, stack) VALUES (?,?,?,?,?)";
		
		$personStack = $person->getStack();
		
		self::prepareAndExecute(QueryBuilder::create($sql, [
			$person->getUuid(),
			$person->getNickname(),
			$person->getName(),
			$person->getBirthdate(),
			is_array($personStack) ? implode("ß", $personStack) : $personStack
		]), "Error saving person");
	}
}