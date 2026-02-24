<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HistoryController;
use App\Controllers\StatisticsController;
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

    // AUTH
    $r->addRoute('POST', '/signin', [AuthController::class, 'userLogin']);
    $r->addRoute('POST', '/signup', [AuthController::class, 'userRegistration']);
    $r->addRoute('POST', '/user-data', [AuthController::class, 'userData']);

    // TRAINING
    $r->addRoute('POST', '/create-training', [TrainingController::class, 'addTraining']);
    $r->addRoute('POST', '/edit-training', [TrainingController::class, 'editTraining']);
    $r->addRoute('POST', '/delete-training', [TrainingController::class, 'deleteTraining']);
    $r->addRoute('POST', '/start-training', [TrainingController::class, 'startTraining']);
    $r->addRoute('POST', '/end-training', [TrainingController::class, 'endTraining']);
    $r->addRoute('POST', '/save-training-history', [HistoryController::class, 'saveTrainingHistory']);

    // EXERCISE
    $r->addRoute('POST', '/new-exercise', [TrainingController::class, 'newExercise']);
    $r->addRoute('POST', '/note-exercise-set', [TrainingController::class, 'addNote']);
    $r->addRoute('POST', '/edit-exercise', [TrainingController::class, 'editExercise']);
    $r->addRoute('POST', '/delete-exercise', [TrainingController::class, 'deleteExercise']);

    // SET
    $r->addRoute('POST', '/add-exercise-set', [TrainingController::class, 'exerciseSet']);
    $r->addRoute('POST', '/edit-exercise-set', [TrainingController::class, 'editSet']);
    $r->addRoute('POST', '/remove-set', [TrainingController::class, 'deleteSet']);

    // HISTORY
    $r->addRoute('POST', '/filter-trainings', [HistoryController::class, 'filterTrainings']);

    // STATISTICS
    $r->addRoute('POST', '/filter-exercise-statistics', [StatisticsController::class, 'filterExercisesStatistics']);

    // VIEWS
    $r->addRoute('GET', '/signin-form', [AuthController::class, 'signInForm']);
    $r->addRoute('GET', '/signup-form', [AuthController::class, 'signUpForm']);
    $r->addRoute('GET', '/user-data-form', [AuthController::class, 'userDataForm']);
    $r->addRoute('GET', '/dashboard', [DashboardController::class, 'dashboard']);
    $r->addRoute('GET', '/history', [HistoryController::class, 'history']);
    $r->addRoute('GET', '/statistics', [StatisticsController::class, 'statistics']);
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
            '/user-data-form',
            '/dashboard',
            '/trainings',
            '/training',
            '/statistics',
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
