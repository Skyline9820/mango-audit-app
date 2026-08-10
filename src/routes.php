<?php

use App\Controllers\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['status' => 'ok', 'framework' => 'Slim']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Rutas de ejemplo (Router)
    $app->get('/users', [UserController::class, 'index']);
    $app->post('/login', [UserController::class, 'login']);
    $app->get('/me', [UserController::class, 'me']);
    $app->post('/logout', [UserController::class, 'logout']);
};
