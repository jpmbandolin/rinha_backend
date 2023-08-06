<?php

namespace ApplicationBase\Infra\Enums;

enum SqlClauseEnum: string implements EnumInterface
{
	case Insert = 'INSERT';
	case Update = 'UPDATE';
	case Delete = 'DELETE';
	case Select = "SELECT";
	case Truncate = "TRUNCATE";
	
	/**
	 * @return string
	 */
	public function label(): string
	{
		return self::getLabel($this);
	}
	
	/**
	 * @param $value
	 *
	 * @return string
	 */
	public static function getLabel($value): string
	{
		return match ($value) {
			self::Insert   => "INSERT",
			self::Update   => "UPDATE",
			self::Delete   => "DELETE",
			self::Select   => "SELECT",
			self::Truncate => "TRUNCATE"
		};
	}
}