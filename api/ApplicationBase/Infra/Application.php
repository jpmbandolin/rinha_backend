<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Environment\{Redis, Database, Environment, Migrations, Slim};
use ApplicationBase\Infra\Slim\{Router, SlimCorsMiddleware, SlimErrorHandler};
use DI\Container;
use DI\Bridge\Slim\Bridge;
use Slim\App;

class Application
{
    private static Container $container;
    private static App $app;

    public static function setupEnvironment(string $envFilePath = "../env.ini"): void
    {
        $ENV = parse_ini_file($envFilePath, true);

        Environment::setupEnvironment(
            database: new Database(
                database: $ENV['DATABASE']['database'],
                user: $ENV['DATABASE']['user'],
                password: $ENV['DATABASE']['password'],
                host: $ENV['DATABASE']['host']
            ),
            migrations: new Migrations(
                dbHost: $ENV['MIGRATIONS']['db_host']
            ),
            redis: new Redis(
                host: $ENV["REDIS"]['host']
            ),
            slim: new Slim(
                basePath: $ENV["SLIM"]['base_path']
            )
        );
    }

    private static function createSlimApp(): void
    {
        self::$app = Bridge::create(self::getSlimContainer());
        self::$app->setBasePath(Environment::getEnvironment()->getSlim()->getBasePath());
        self::$app->addRoutingMiddleware();
        self::$app->addBodyParsingMiddleware();
        self::$app->add(new SlimCorsMiddleware);
        self::setCustomErrorHandler();
        self::declareRoutes();

    }

    private static function setCustomErrorHandler(): void
    {
        (self::$app->addErrorMiddleware(false, false, false))
            ->setDefaultErrorHandler(
                new SlimErrorHandler(self::$app->getCallableResolver(), self::$app->getResponseFactory())
            );
    }

    public static function runSlimApp(): void
    {
        self::getSlimApp()->run();
    }

    public static function getSlimApp(): App
    {
        if (!isset(self::$app)) {
            self::createSlimApp();
        }

        return self::$app;
    }

    public static function getSlimContainer(): Container
    {
        if (!isset(self::$container)) {
            self::$container = new Container();
        }

        return self::$container;
    }

    private static function declareRoutes(): void
    {
        Router::declareRoutes();
    }
}