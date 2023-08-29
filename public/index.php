<?php

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set('DB', function() { return new App\Model\DB(); });

require __DIR__ . '/../app/middlewares.php';

require __DIR__ . '/../app/routes.php';

$app->run();
