<?php
session_start();

require '../vendor/autoload.php';

use core\ErrorHandler;
$router = new \core\Router();

set_error_handler(['core\ErrorHandler', 'handle']);
set_exception_handler(['core\ErrorHandler', 'handle']);

require '../src/routes.php';
require_once '../src/apiRoutes.php';

$router->run();