<?php


namespace ApplicationBase\Infra\Abstracts;

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

		if ($status === 201){
			return $response->withStatus($status);
		}

		return $response->withHeader('Content-Type', 'application/json')->withBody($resBody)->withStatus($status);
	}
}