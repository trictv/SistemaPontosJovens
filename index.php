<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

if ($uri === '') {
    $uri = '/';
}

$routes = [
    '/' => ['PublicController', 'index'],
    '/login' => ['AuthController', 'login'],
    '/logout' => ['AuthController', 'logout'],
    '/admin' => ['AdminController', 'dashboard'],
    '/admin/grupos' => ['AdminController', 'grupos'],
    '/admin/grupos/criar' => ['AdminController', 'criarGrupo'],
    '/admin/usuarios' => ['AdminController', 'usuarios'],
    '/admin/usuarios/criar' => ['AdminController', 'criarUsuario'],
    '/admin/atividades' => ['AdminController', 'atividades'],
    '/admin/atividades/criar' => ['AdminController', 'criarAtividade'],
    '/admin/pontuacao' => ['AdminController', 'pontuacao'],
    '/admin/pontuacao/lancar' => ['AdminController', 'lancarPontuacao'],
    '/supervisor' => ['SupervisorController', 'dashboard'],
    '/supervisor/registrar' => ['SupervisorController', 'registrarAtividade'],
    '/supervisor/salvar-registro' => ['SupervisorController', 'salvarRegistro'],
];

if (array_key_exists($uri, $routes)) {
    $controllerName = 'Controllers\\' . $routes[$uri][0];
    $methodName = $routes[$uri][1];

    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            http_response_code(404);
            echo "Method not found.";
        }
    } else {
        http_response_code(404);
        echo "Controller not found.";
    }
} else {
    http_response_code(404);
    echo "404 Not Found.";
}
