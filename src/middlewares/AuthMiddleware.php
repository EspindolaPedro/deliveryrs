<?php

namespace src\middlewares;

use core\Middleware;
use core\Response;
use src\handlers\UserHandler;

class AuthMiddleware extends Middleware
{
    public function handle($request, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $base = "http://localhost/deliveryrs/public/admin";
        if (!isset($_SESSION['token']) || !isset($_SESSION['user']) ) {
            header("Location: $base");
            exit;
        }

        $user = UserHandler::checkLogin($_SESSION['token']);
        if (!$user) {
            unset($_SESSION['token']); // Remove o token inválido
            header("Location: $base");
            exit;
        }

        return $next();
    }
}
