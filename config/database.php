<?php

/**
 * Configura Eloquent (el ORM que usa Laravel) para funcionar de forma
 * independiente dentro de Slim, sin necesidad de instalar Laravel completo.
 * Esto es el equivalente a Flask-SQLAlchemy en el mundo PHP.
 */

use Illuminate\Database\Capsule\Manager as Capsule;

return function (): Capsule {
    $capsule = new Capsule();

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port'      => $_ENV['DB_PORT'] ?? '3306',
        'database'  => $_ENV['DB_DATABASE'] ?? 'mango_app',
        'username'  => $_ENV['DB_USERNAME'] ?? 'root',
        'password'  => $_ENV['DB_PASSWORD'] ?? '',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ]);

    // Hace que Capsule esté disponible globalmente (estilo "Facade" de Laravel)
    $capsule->setAsGlobal();
    // Activa el sistema de eventos de Eloquent (necesario para timestamps, etc.)
    $capsule->bootEloquent();

    return $capsule;
};
