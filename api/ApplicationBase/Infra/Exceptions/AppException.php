<?php

namespace ApplicationBase\Infra\Exceptions;

abstract class AppException extends \Exception
{
	abstract public function getDefaultHttpStatusCode():int;
	
	protected ?int $statusCode = null;
	
	public function getDetailedErrorMessage(): array
	{
		$exceptionStack = [$this];
		$previous = $this->getPrevious();
		
		while($previous){
			$exceptionStack[]	= $previous;
			$previous			= $previous->getPrevious();
		}
		
		$exceptionStack = array_reverse($exceptionStack);
		
		$detailedMessage = [
			"message"	=> $this->getMessage(),
			"trace"		=> $this->getTraceAsString(),
			"line"      => $this->getLine(),
			"file"      => $this->getFile()
		];
		foreach ($exceptionStack as $index=>$exception) {
			$detailedMessage['message'] .= " #" . $index+1 . " " . $exception->getMessage() . " " . $detailedMessage["file"] . " " . $detailedMessage["line"];
		}
		
		return $detailedMessage;
	}
	
	public function getStatusCode(): ?int
	{
		return $this->statusCode;
	}
	
	public function setStatusCode(int $statusCode): self
	{
		$this->statusCode = $statusCode;
		return $this;
	}
}