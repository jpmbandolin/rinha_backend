<?php

namespace ApplicationBase\Infra\Exceptions;

class RuntimeException extends AppException
{

	public function getDefaultHttpStatusCode(): int
	{
		return 500;
	}
}