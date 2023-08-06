<?php

namespace Modules\People\Application\Post;

use Throwable;
use ApplicationBase\Infra\Redis;
use Modules\People\Domain\Person;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{DatabaseException, InvalidValueException};

class Post extends ControllerAbstract
{
	/**
	 * @param PostDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException|InvalidValueException|Throwable
	 */
	public function run(PostDTO $dto): ResponseInterface
	{
		if (!isset($dto->apelido, $dto->nome, $dto->nascimento)){
			throw new InvalidValueException("Missing properties");
		}
		
		if (strlen($dto->apelido) > 32 || strlen($dto->nome) > 1000 || !self::isDateValid($dto->nascimento)) {
			throw new InvalidValueException("Invalid values provided");
		}

		foreach ($dto->stack as $item) {
			if (!is_string($item) || strlen($item) > 32){
				throw new InvalidValueException("Invalid values provided for stack items");
			}
		}

		if (Redis::getInternalConnection()->get($dto->apelido)) {
			throw new InvalidValueException("Nickname is already in use");
		}
		
		try {
			$person = (new Person(
				nickname: $dto->apelido, name: $dto->nome, birthdate: $dto->nascimento, stack: $dto->stack
			))->save();
		}catch (DatabaseException $e) {
			if (str_contains($e->getPrevious()?->getMessage(), "1062 Duplicate entry")){
				$e->setStatusCode(422);
			}

			throw $e;
		}
		
		Redis::getInternalConnection()->set($dto->apelido, 1);
		
		return $this->replyRequest(status: 201, additionalHeaders: ["Location" => "/pessoas/" . $person->getUuid()]);
	}

	private static function isDateValid($date): bool
	{
		$d = \DateTime::createFromFormat("Y-m-d", $date);
		
		return $d && $d->format("Y-m-d") === $date;
	}
}