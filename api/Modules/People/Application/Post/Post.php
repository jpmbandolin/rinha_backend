<?php

namespace Modules\People\Application\Post;

use Throwable;
use ApplicationBase\Infra\Redis;
use ApplicationBase\Infra\Application;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{InvalidValueException, RuntimeException};

class Post extends ControllerAbstract
{
	/**
	 * @param PostDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws InvalidValueException|Throwable
	 */
	public function run(PostDTO $dto): ResponseInterface
	{
		if (!isset($dto->apelido, $dto->nome, $dto->nascimento)){
			throw new InvalidValueException("Missing properties");
		}
		
		$dto->stack = $dto->stack ?? [];
		
		if (strlen($dto->apelido) > 32 || strlen($dto->nome) > 100 || !self::isDateValid($dto->nascimento)) {
			throw new InvalidValueException("Invalid values provided");
		}
		
		$stackString = "";

		foreach ($dto->stack as $index => $item) {
			if (!is_string($item) || strlen($item) > 32){
				throw new InvalidValueException("Invalid values provided for stack items");
			}
			
			$stackString .= (($index === 0 ? "" : "ß") . $item);
		}

		if (Redis::getInternalConnection()->get($dto->apelido)) {
			throw new InvalidValueException("Nickname is already in use");
		}
		
		$uuid = uniqid("", true);
		$jsonEncodedMessage = json_encode([
	        "id" =>  $uuid,
	        "apelido" => $dto->apelido,
	        "nome" => $dto->nome,
	        "nascimento" => $dto->nascimento,
	        "stack" => $stackString
	    ]);
		
		Application::startTimer("postRequestPostToRedis");
		try{
			Redis::set($uuid, $jsonEncodedMessage, 11000);
			Redis::getInternalConnection()->set($dto->apelido, 1);
			Redis::getInternalConnection()->publish("createUser", $jsonEncodedMessage);
		}catch (Throwable $t){
			throw new RuntimeException("Error publishing user data to redis", previous: $t);
		}
		Application::endTimer("postRequestPostToRedis");

		return $this->replyRequest(status: 201, additionalHeaders: ["Location" => "/pessoas/" .$uuid]);
	}

	private static function isDateValid($date): bool
	{
		$d = \DateTime::createFromFormat("Y-m-d", $date);
		
		return $d && $d->format("Y-m-d") === $date;
	}
}