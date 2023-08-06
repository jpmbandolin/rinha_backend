<?php

namespace Modules\People\Application\Get;

use Modules\People\Domain\Person;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{NotFoundException, DatabaseException};

class Get extends ControllerAbstract
{
	/**
	 * @throws DatabaseException
	 * @throws NotFoundException
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
			], Person::search($dto->t));
		}

		return $this->replyRequest($response);
	}
}