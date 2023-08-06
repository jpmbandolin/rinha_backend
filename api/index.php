<?php

require_once '../vendor/autoload.php';


use ApplicationBase\Infra\Application;

Application::setupEnvironment();
Application::runSlimApp();