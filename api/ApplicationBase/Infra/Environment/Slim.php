<?php

namespace ApplicationBase\Infra\Environment;

class Slim
{
 public function __construct(
     private readonly string $basePath
 )
 {

 }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }
}