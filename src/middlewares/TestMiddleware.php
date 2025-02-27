<?php
namespace src\middlewares;

use core\Middleware;
use core\Response;

class TestMiddleware extends Middleware {
    public function handle($request, $next) {
        if (!isset($_GET['token']) || $_GET['token'] !== '123') {
            Response::json(['error' => 'Acesso negado! Token inválido.'], 403);
            exit;
        }
        return $next();
    }
}
