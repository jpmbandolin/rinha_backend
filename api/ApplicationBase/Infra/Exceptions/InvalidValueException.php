<?php

namespace ApplicationBase\Infra\Exceptions;

class InvalidValueException extends AppException
{

	public function getDefaultHttpStatusCode(): int
	{
		return 422;
	}
}