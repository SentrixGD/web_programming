<?php

require_once "../vendor/autoload.php";
require_once "../framework/autoload.php";

require_once "../controllers/BaseGameTwigController.php";
require_once "../controllers/MainController.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/GameCreateController.php";
require_once "../controllers/GameUpdateController.php";
require_once "../controllers/TypeCreateController.php";
require_once "../controllers/GameDeleteController.php";
require_once "../controllers/GameController.php";
require_once "../controllers/Controller404.php";

$loader = new \Twig\Loader\FilesystemLoader("../views");
$twig = new \Twig\Environment($loader);

$dbPassword = getenv("DB_PASSWORD");
if ($dbPassword === false) {
    throw new RuntimeException("DB_PASSWORD environment variable is not set");
}

$pdo = new PDO(
    "pgsql:host=localhost;port=5432;dbname=my_first_db",
    "postgres",
    $dbPassword,
    [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$router = new Router($twig, $pdo);
$router->add("/", MainController::class);
$router->add("/search", SearchController::class);
$router->add("/games/create", GameCreateController::class);
$router->add("/types/create", TypeCreateController::class);
$router->add("/games/delete/(?P<id>\d+)", GameDeleteController::class);
$router->add("/games/(?P<id>\d+)/edit", GameUpdateController::class);
$router->add("/games/(?P<id>\d+)", GameController::class);

$router->get_or_default(Controller404::class);
