<?php

use App\Session;
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Carga variables de entorno (.env)
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

// Inicia el ORM (Eloquent / Capsule)
$capsuleFactory = require __DIR__ . '/../config/database.php';
$capsuleFactory();

// Contenedor de dependencias simple (para inyectar el manejador de sesión)
$container = new Container();
$container->set(Session::class, function () {
    return new Session();
});

AppFactory::setContainer($container);
$app = AppFactory::create();

// Middleware de sesión: arranca la sesión de forma segura en cada request
$app->add(function ($request, $handler) use ($container) {
    /** @var Session $session */
    $session = $container->get(Session::class);
    $session->start();
    $response = $handler->handle($request);
    $session->save();
    return $response;
});

$app->addErrorMiddleware(
    (bool) ($_ENV['APP_DEBUG'] ?? false),
    true,
    true
);

// Registra las rutas (Router)
(require __DIR__ . '/../src/routes.php')($app);

$app->run();
