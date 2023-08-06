<?php

namespace ApplicationBase\Infra\Abstracts;

use ApplicationBase\Infra\Attributes\{ArrayTypeAttribute, OptionalAttribute};
use ApplicationBase\Infra\Exceptions\InvalidValueException;
use ReflectionClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

abstract class DTOAbstract
{
	final public function __set($name, $value){
		return false;
	}
}