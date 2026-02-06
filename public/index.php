<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TrainingController;
use App\Middlewares\AuthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // GET
    $r->addRoute('GET', '/logout', [AuthController::class, 'userLogout']);
    $r->addRoute('GET', '/trainings', [TrainingController::class, 'displayAllTrainings']);
    $r->addRoute('GET', '/training', [TrainingController::class, 'displayTraining']);
    $r->addRoute('GET', '/set-id', [TrainingController::class, 'getEditSet']);

    // POST
    $r->addRoute('POST', '/signin', [AuthController::class, 'userLogin']);
    $r->addRoute('POST', '/signup', [AuthController::class, 'userRegistration']);
    $r->addRoute('POST', '/create-training', [TrainingController::class, 'addTraining']);
    $r->addRoute('POST', '/add-exercise-set', [TrainingController::class, 'exerciseSet']);
    $r->addRoute('POST', '/edit-exercise-set', [TrainingController::class, 'editSet']);

    // VIEWS
    $r->addRoute('GET', '/signin-form', [AuthController::class, 'signInForm']);
    $r->addRoute('GET', '/signup-form', [AuthController::class, 'signUpForm']);
    $r->addRoute('GET', '/dashboard', [DashboardController::class, 'dashboard']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $protectedRoutes = [
            '/dashboard',
            '/trainings',
            '/training',
        ];
        if (in_array($uri, $protectedRoutes, true)) {
            (new AuthMiddleware)->userAccess();
        }

        if (is_callable($handler)) {
            return $handler($vars);
        }
        [$class, $method] = $handler;
        $controller = new $class();
        return $controller->$method($vars);
}
