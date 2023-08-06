<?php

namespace ApplicationBase\Infra\Exceptions;

class DatabaseException extends AppException
{

	public function getDefaultHttpStatusCode(): int
	{
		return 500;
	}
}