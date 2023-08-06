<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Application;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Modules\People\Application\GetPeopleCount\Get;

class Router
{
    /**
     * @return void
     */
	public static function declareRoutes(): void
	{
        $app = Application::getSlimApp();

		$app->options(
			'/{routes:.+}', function (RequestInterface $request, Response $response): Response {
				return $response;
			}
		);
		
		$app->get('/contagem-pessoas', [Get::class, 'run']);
		$app->group("/pessoas", \Modules\People\Router::class);
	}
}