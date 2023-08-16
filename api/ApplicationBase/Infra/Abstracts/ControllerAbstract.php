<?php


namespace ApplicationBase\Infra\Abstracts;

use ApplicationBase\Infra\Application;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

abstract class ControllerAbstract
{
	
	/**
	 * @param mixed|null             $body
	 * @param int                    $status
	 * @param ResponseInterface|null $response
	 * @param array                  $additionalHeaders
	 *
	 * @return ResponseInterface
	 */
	final protected function replyRequest(mixed $body = null, int $status = 200, ResponseInterface $response = null, array $additionalHeaders = []): ResponseInterface
	{
		if($response === null){
			$response = new Response;
		}

		$resBody = $response->getBody();
		$resBody->write(json_encode($body));
		
		if (count($additionalHeaders)){
			foreach ($additionalHeaders as $headerName => $headerValue){
				$response = $response->withHeader($headerName, $headerValue);
			}
		}
		
		foreach (Application::getTimers() as $timerName => $timer){
			$response = $response->withHeader($timerName, ($timer["end"] - $timer["start"]));
		}

		if ($status === 201){
			return $response->withStatus($status);
		}
		
		$endTime = microtime(true);

		return $response
			->withHeader('Content-Type', 'application/json')
			->withHeader("duration", $endTime - Application::getInitialExecutionTime())
			->withHeader("startTime", Application::getInitialExecutionTime())
			->withHeader("endTime", $endTime)
			->withHeader("timers", json_encode(Application::getTimers()))
			->withBody($resBody)
			->withStatus($status);
	}
}