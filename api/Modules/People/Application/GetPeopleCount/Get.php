<?php

namespace Modules\People\Application\GetPeopleCount;

use Modules\People\Domain\Person;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class Get extends ControllerAbstract
{
	/**
	 * @return ResponseInterface
	 * @throws DatabaseException
	 */
	public function run(): ResponseInterface
	{
		return $this->replyRequest(Person::getPeopleCount());
	}
}