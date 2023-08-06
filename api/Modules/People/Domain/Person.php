<?php

namespace Modules\People\Domain;

class Person
{
	use Actions;

	public function __construct(
		private readonly string $nickname,   // unique, maxLength === 32
		private readonly string $name,       // maxLength === 100
		private readonly string $birthdate,  // format === AAA-MM-DD
		private array|string    $stack = [], // Each string should have at most 32 characters
		private ?string         $uuid = null
	){
		$this->uuid = $this->uuid ?? uniqid("", true);
		$this->stack = is_string($this->stack) ? explode("ß", $this->stack) : $this->stack;
	}
	
	/**
	 * @return string
	 */
	public function getNickname(): string
	{
		return $this->nickname;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	
	/**
	 * @return string
	 */
	public function getBirthdate(): string
	{
		return $this->birthdate;
	}
	
	/**
	 * @return array|string
	 */
	public function getStack(): array|string
	{
		return $this->stack;
	}
	
	/**
	 * @return null|string
	 */
	public function getUuid(): ?string
	{
		return $this->uuid;
	}
}