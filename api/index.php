<?php

require_once '../vendor/autoload.php';


use ApplicationBase\Infra\Application;

Application::setInitialExecutionTime(microtime(true));
Application::startTimer("setupEnv");
Application::setupEnvironment();
Application::endTimer("setupEnv");
Application::runSlimApp();