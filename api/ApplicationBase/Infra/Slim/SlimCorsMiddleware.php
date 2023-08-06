<?php

namespace ApplicationBase\Infra\Slim;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SlimCorsMiddleware
{
	/**
	 * @param Request $request
	 * @param         $handler
	 * @return Response
	 */
	public function __invoke(Request $request, $handler):Response{
		$request = $request->withParsedBody(json_decode($request->getBody()));

		$response = $handler->handle($request);

		return $response
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	}
}