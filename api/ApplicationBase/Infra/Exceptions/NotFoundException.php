<?php

namespace ApplicationBase\Infra\Exceptions;

class NotFoundException extends AppException
{

	public function getDefaultHttpStatusCode(): int
	{
		return 404;
	}
}