<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Application;
use ApplicationBase\Infra\Environment\Environment;
use ApplicationBase\Infra\Exceptions\AppException;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Handlers\ErrorHandler;
use Throwable;
use ApplicationBase\Infra\DiscordIntegration\Embed;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\DiscordIntegration\WebhookNotification;

class SlimErrorHandler extends ErrorHandler
{

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return ResponseInterface
     * @throws JsonException
     */
	public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
	{
		$app = Application::getSlimApp();

		$payload = [];
        $isNotFoundRoute = $exception instanceof HttpNotFoundException;

        if ($isNotFoundRoute){
            $handledException = new NotFoundException("The requested route was not found.", previous: $exception);
        }else if (!is_a($exception, AppException::class)){
			$handledException = new RuntimeException("Internal Server Error.", previous: $exception);
		}else {
            $handledException = $exception;
        }

		$payload['error'] = $handledException->getMessage();

        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
        );
		
		$responseStatus = $handledException->getStatusCode() ?? $handledException->getDefaultHttpStatusCode();
		
		
		if (!in_array($responseStatus, [200, 201, 404, 422, 400])){
			trigger_error(json_encode($handledException->getDetailedErrorMessage()), E_USER_WARNING);
		}

		return $response
			->withStatus($responseStatus)
			->withHeader('Content-Type', 'application/json')
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	}
	
}
