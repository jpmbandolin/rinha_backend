<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;
use ReflectionClass;
use ReflectionException;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

readonly class DtoBuilder
{
	public function __construct(private string $className){}

	/**
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{
		$body       = $request->getParsedBody();
		$query      = $request->getQueryParams();
		$arguments  = RouteContext::fromRequest($request)->getRoute()?->getArguments();

		$dto = new $this->className;

		$dto = $this->build($body ?? [], $dto);
		$dto = $this->build($query, $dto);
		$dto = $this->build($arguments ??[], $dto);

		$container = Application::getSlimContainer();
		$container->set($dto::class, $dto);
		$request = $request->withParsedBody($dto);

		return $handler->handle($request);
	}

	/**
	 * @param array|object $input
	 * @param DTOAbstract $dto
	 * @return DTOAbstract
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	private function build(array|object $input, DTOAbstract $dto):DTOAbstract{
		/**
		 * Converte arrays para objetos e retorna o próprio objeto de DTO caso o input seja vazio.
		 */
		if (is_array($input)){
			if (empty($input)){
				return $dto;
			}

			$input = (object)$input;
		}

		$reflector  = new ReflectionClass($dto::class);
		$properties = $reflector->getProperties();

		/**
		 * Percorre as propriedades do DTO
		 */
		foreach ($properties as $property){
			$propertyName = $property->getName();
			$propertyType = $property->getType()?->getName();

			/**
			 * Caso os parâmetros de entrada não estejam definidos,
			 * o loop continua sem executar mais ações.
			 */
			if (!isset($input->{$propertyName})){
				continue;
			}

			if (in_array($propertyType, ["int", "string", "bool"])){ //Tipos primitivos são simplesmente atribuidos
				$dto->{$propertyName} = $input->{$propertyName};
			}else if($propertyType === "array"){
				if (!is_array($input->{$propertyName})){ //Validação para tipo inválido de input
					throw new InvalidValueException($propertyName . " should be an array");
				}
				
				$dto->{$propertyName} = $input->{$propertyName};
			} else if (is_a(new $propertyType, DTOAbstract::class)){ //Validação de propriedade do DTO, todos os objetos devem ser um DTO
				if (is_object($input->{$propertyName})){ //Validação se a propriedade do input é um objeto.
					$dto->{$propertyName} = $this->build($input->{$propertyName}, new $propertyType);
				}else{
					throw new InvalidValueException('The object should be an instance of DtoAbstract');
				}
			}else{ //Lança erro caso nenhum dos tipos acima tenham sido encontrados
				throw new InvalidValueException('Invalid value supplied');
			}
		}

		return $dto;
	}
}