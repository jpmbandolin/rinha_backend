<?php

namespace Modules\People\Application\Get;

use RedisException;
use ApplicationBase\Infra\Redis;
use Modules\People\Domain\Person;
use ApplicationBase\Infra\Application;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{InvalidValueException, NotFoundException, DatabaseException};

class Get extends ControllerAbstract
{
	/**
	 * @throws DatabaseException
	 * @throws NotFoundException|InvalidValueException|RedisException
	 */
	public function run(GetDTO $dto): ResponseInterface
	{
		Application::startTimer("getRequest");
		if (isset($dto->uuid)){
			$redisResponse = Redis::get($dto->uuid);
			
			if ($redisResponse){
				return $this->replyRequest($redisResponse);
			}
			
			$person = Person::getById($dto->uuid) ?? throw new NotFoundException("The requested user was not found");

			$response = [
				"id" => $person->getUuid(),
				"apelido" => $person->getNickname(),
				"nome" => $person->getName(),
				"stack" => $person->getStack()
			];
		} else {
			Application::startTimer("getByTerm");
			
			$response = array_map(static fn(Person $person) => [
				"id" => $person->getUuid(),
				"apelido" => $person->getNickname(),
				"nome" => $person->getName(),
				"stack" => $person->getStack()
			], Person::search(
				$dto->t ??  throw (new InvalidValueException("The term should be present on the request"))->setStatusCode(400)
			));
			
			Application::endTimer("getByTerm");
		}
		
		Application::endTimer("getRequest");
		return $this->replyRequest($response);
	}
}