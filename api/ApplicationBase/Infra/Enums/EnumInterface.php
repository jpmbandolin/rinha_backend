<?php

namespace ApplicationBase\Infra\Enums;

interface EnumInterface
{
	public function label():string;
	public static function getLabel($value):string;
}