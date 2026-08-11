<?php

namespace App\Controllers;

use App\Models\User;
use App\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /** GET /users — lista de usuarios usando el ORM */
    public function index(Request $request, Response $response): Response
    {
        $users = User::all();

        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /** POST /login — ejemplo de manejo de sesión */
    public function login(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $user = User::where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            $response->getBody()->write(json_encode(['error' => 'Credenciales inválidas']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $this->session->set('user_id', $user->id);
        $this->session->set('user_name', $user->name);

        $response->getBody()->write(json_encode(['message' => 'Sesión iniciada', 'user' => $user->name]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /** GET /me — ejemplo de lectura de sesión */
    public function me(Request $request, Response $response): Response
    {
        if (!$this->session->has('user_id')) {
            $response->getBody()->write(json_encode(['error' => 'No hay sesión activa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $response->getBody()->write(json_encode([
            'user_id' => $this->session->get('user_id'),
            'user_name' => $this->session->get('user_name'),
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /** POST /logout */
    public function logout(Request $request, Response $response): Response
    {
        $this->session->clear();
        $response->getBody()->write(json_encode(['message' => 'Sesión cerrada']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
