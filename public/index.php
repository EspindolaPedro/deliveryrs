<?php
session_start();

require '../vendor/autoload.php';

$router = new \core\Router();

set_error_handler(['core\ErrorHandler', 'handleError']);
set_exception_handler(['core\ErrorHandler', 'handleException']);

require_once '../src/routes.php';
require_once '../src/apiRoutes.php';

$router->run();