<?php

define('ROOT_DIR', __DIR__ . '/..');
define('SRC_DIR', __DIR__);
define('CFG_DIR', ROOT_DIR . '/config');
define('LOG_DIR', ROOT_DIR . '/log');
define('PUBLIC_DIR', __DIR__ . '/../public');
define('CONTROLLER_DIR', SRC_DIR . '/controller');
define('MODEL_DIR', SRC_DIR . '/model');
define('VIEW_DIR', SRC_DIR . '/view');
define('REPOSITORY_DIR', SRC_DIR . '/repository');

require ROOT_DIR . '/vendor/autoload.php';

session_start();

// Error handling
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Custom function
function shutdown() {
    if (($error = error_get_last())) {
        var_dump($error);
    }
}

register_shutdown_function('shutdown');

$controllerMap = array(
    '/' => 'Controller'
);

$pathInfo = App::getInstance()->getPathInfo();
$action = App::getInstance()->getAction();

if (!isset($controllerMap[$pathInfo])) {
    die("Controller non definito per $pathInfo");
}

try {
    $controllerClass = $controllerMap[$pathInfo];
    $controller = new $controllerClass();
    if (!is_callable(array($controller, $action))) {
        die("Action: $action non prevista per il controller: $controllerClass");
    }
    $controller->$action();
} catch (Exception $ex) {
    die($ex->getMessage());
}    