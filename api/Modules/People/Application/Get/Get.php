<?php

namespace Modules\People\Application\Get;

use Modules\People\Domain\Person;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{InvalidValueException, NotFoundException, DatabaseException};

class Get extends ControllerAbstract
{
	/**
	 * @throws DatabaseException
	 * @throws NotFoundException|InvalidValueException
	 */
	public function run(GetDTO $dto): ResponseInterface
	{
		if (isset($dto->uuid)){
			$person = Person::getById($dto->uuid) ?? throw new NotFoundException("The requested user was not found");

			$response = [
				"id" => $person->getUuid(),
				"apelido" => $person->getNickname(),
				"nome" => $person->getName(),
				"stack" => $person->getStack()
			];
		} else {
			$response = array_map(static fn(Person $person) => [
				"id" => $person->getUuid(),
				"apelido" => $person->getNickname(),
				"nome" => $person->getName(),
				"stack" => $person->getStack()
			], Person::search(
				$dto->t ??  throw (new InvalidValueException("The term should be present on the request"))->setStatusCode(400)
			));
		}

		return $this->replyRequest($response);
	}
}