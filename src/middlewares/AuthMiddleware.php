<?php
namespace src\middlewares;

use core\Middleware;
use core\Response;

class AuthMiddleware extends Middleware {
    public function handle($request, $next) {
        session_start();
        if (!isset($_SESSION['user'])) {
            Response::json(['error' => 'Unauthorized'], 401);
            exit;
        }
        return $next();
    }
}
