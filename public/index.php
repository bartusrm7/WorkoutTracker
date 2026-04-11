<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HistoryController;
use App\Controllers\ProfileController;
use App\Controllers\StatisticsController;
use App\Controllers\TrainingController;
use App\Database\Database;
use App\Middlewares\AuthMiddleware;
use App\Repositories\AuthRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\StatisticsRepository;
use App\Repositories\TrainingRepository;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\HistoryService;
use App\Services\MailerService;
use App\Services\ProfileService;
use App\Services\StatisticsService;
use App\Services\TrainingService;
use App\Sessions\Session;
use App\Validators\AuthValidation;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = new Database();
$session = new Session();
$mailer = new MailerService();

// REPOSITORIES
$authRepository = new AuthRepository($db);
$dashboardRepository = new DashboardRepository($db);
$trainingRepository = new TrainingRepository($db);
$historyRepository = new HistoryRepository($db);
$statisticsRepository = new StatisticsRepository($db);
$profileRepository = new ProfileRepository($db);

// VALIDATIONS
$authValidator = new AuthValidation();

// SERVICES
$authService = new AuthService($authRepository, $authValidator, $mailer);
$dashboardService = new DashboardService($dashboardRepository);
$trainingService = new TrainingService($trainingRepository);
$historyService = new HistoryService($historyRepository);
$statisticsService = new StatisticsService($statisticsRepository);
$profileService = new ProfileService($profileRepository);

// CONTROLLERS
$authController = new AuthController($authService, $session, $mailer);
$dashboardController = new DashboardController($dashboardService, $session);
$trainingController = new TrainingController($trainingService);
$historyController = new HistoryController($historyService);
$statisticsController = new StatisticsController($statisticsService);
$profileController = new ProfileController($profileService);

$controllers = [
    AuthController::class        => $authController,
    DashboardController::class   => $dashboardController,
    TrainingController::class    => $trainingController,
    HistoryController::class     => $historyController,
    StatisticsController::class  => $statisticsController,
    ProfileController::class     => $profileController,
];

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // GET
    $r->addRoute('GET', '/logout', [AuthController::class, 'userLogout']);
    $r->addRoute('GET', '/forget-password-email-form', [AuthController::class, 'forgetPasswordEmailForm']);
    $r->addRoute('GET', '/reset-password-form', [AuthController::class, 'resetPasswordForm']);
    $r->addRoute('GET', '/trainings', [TrainingController::class, 'displayAllTrainings']);
    $r->addRoute('GET', '/training', [TrainingController::class, 'displayTraining']);
    $r->addRoute('GET', '/set-id', [TrainingController::class, 'getEditSet']);

    // POST

    // AUTH
    $r->addRoute('POST', '/signin', [AuthController::class, 'userLogin']);
    $r->addRoute('POST', '/signup', [AuthController::class, 'userRegistration']);
    $r->addRoute('POST', '/forget-password-email', [AuthController::class, 'forgetPasswordEmail']);
    $r->addRoute('POST', '/reset-password', [AuthController::class, 'resetPassword']);
    $r->addRoute('POST', '/user-data', [AuthController::class, 'userData']);

    // TRAINING
    $r->addRoute('POST', '/create-training', [TrainingController::class, 'addTraining']);
    $r->addRoute('POST', '/edit-training', [TrainingController::class, 'editTraining']);
    $r->addRoute('POST', '/delete-training', [TrainingController::class, 'deleteTraining']);
    $r->addRoute('POST', '/start-training', [TrainingController::class, 'startTraining']);
    $r->addRoute('POST', '/end-training', [TrainingController::class, 'endTraining']);
    $r->addRoute('POST', '/save-training-history', [HistoryController::class, 'saveTrainingHistory']);
    $r->addRoute('POST', '/get-pr', [TrainingController::class, 'getPR']);
    $r->addRoute('POST', '/exercises-preview', [TrainingController::class, 'exercisesPreview']);

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

    // PROFILE
    $r->addRoute('POST', '/edit-profile', [ProfileController::class, 'editProfile']);

    // VIEWS
    $r->addRoute('GET', '/signin-form', [AuthController::class, 'signInForm']);
    $r->addRoute('GET', '/signup-form', [AuthController::class, 'signUpForm']);
    $r->addRoute('GET', '/user-data-form', [AuthController::class, 'userDataForm']);
    $r->addRoute('GET', '/dashboard', [DashboardController::class, 'dashboard']);
    $r->addRoute('GET', '/history', [HistoryController::class, 'history']);
    $r->addRoute('GET', '/statistics', [StatisticsController::class, 'statistics']);
    $r->addRoute('GET', '/profile', [ProfileController::class, 'profile']);
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
            '/profile'
        ];
        if (in_array($uri, $protectedRoutes, true)) {
            (new AuthMiddleware)->userAccess();
        }

        if (is_callable($handler)) {
            return $handler($vars);
        }
        [$class, $method] = $handler;
        $controller = $controllers[$class];
        return $controller->$method($vars);
}
