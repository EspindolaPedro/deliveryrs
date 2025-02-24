<?php
namespace core;

use src\Config;
use core\Request;

class RouterBase {
    private $middlewares = [];
    private static $routes = []; // 🔹 Rotas são armazenadas em um único array estático

    // 🔹 Adiciona uma rota GET
    public function get($route, $controllerAction) {
        self::$routes['get'][$route] = $controllerAction;
    }

    // 🔹 Adiciona uma rota POST
    public function post($route, $controllerAction) {
        self::$routes['post'][$route] = $controllerAction;
    }

    // 🔹 Adiciona uma rota PUT
    public function put($route, $controllerAction) {
        self::$routes['put'][$route] = $controllerAction;
    }

    // 🔹 Adiciona uma rota DELETE
    public function delete($route, $controllerAction) {
        self::$routes['delete'][$route] = $controllerAction;
    }

    // Define middlewares para rotas específicas
    public function middleware($middleware, $routes) {
        foreach ($routes as $route) {
            if (!isset($this->middlewares[$route])) {
                $this->middlewares[$route] = [];
            }
            $this->middlewares[$route][] = "src\\middlewares\\" . $middleware;
        }
    }

    // 🔹 Executa a lógica de roteamento
    public function run() {
        $method = Request::getMethod();
        $url = Request::getUrl();

        if (isset($this->middlewares[$url])) {
            $this->executeMiddlewares($url, 0, function() use ($method, $url) {
                $this->executeController($method, $url);
            });
        } else {
            $this->executeController($method, $url);
        }
    }

    // Executa middlewares recursivamente (cadeia de execução)
    private function executeMiddlewares($url, $index, $next) {
        if (!isset($this->middlewares[$url][$index])) {
            return $next();
        }

        $middlewareClass = $this->middlewares[$url][$index];
        $middleware = new $middlewareClass();

        $middleware->handle($url, function() use ($url, $index, $next) {
            $this->executeMiddlewares($url, $index + 1, $next);
        });
    }

    // 🔹 Executa o controlador correspondente à rota
    private function executeController($method, $url) {
        $controller = Config::ERROR_CONTROLLER;
        $action = Config::DEFAULT_ACTION;
        $args = [];

        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $route => $callback) {
                $pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $route);

                if (preg_match('#^(' . $pattern . ')*$#i', $url, $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);

                    $itens = [];
                    if (preg_match_all('(\{[a-z0-9]{1,}\})', $route, $m)) {
                        $itens = preg_replace('(\{|\})', '', $m[0]);
                    }

                    $args = [];
                    foreach ($matches as $key => $match) {
                        $args[$itens[$key]] = $match;
                    }

                    $callbackSplit = explode('@', $callback);
                    $controller = $callbackSplit[0];
                    if (isset($callbackSplit[1])) {
                        $action = $callbackSplit[1];
                    }

                    break;
                }
            }
        }

        // 🔹 Diferencia API e Views
        if (strpos($url, '/api') === 0) {
            $controller = "\src\controllers\Api\\$controller";
        } else {
            $controller = "\src\controllers\\$controller";
        }

        if (!class_exists($controller)) {
            $this->sendErrorResponse(404, 'Controller not found: ' . $controller);
        }

        $definedController = new $controller();

        if (!method_exists($definedController, $action)) {
            $this->sendErrorResponse(404, 'Method not found: ' . $action);
        }

        $definedController->$action($args);
    }

    // 🔹 Envia uma resposta de erro para APIs
    private function sendErrorResponse($status, $message) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode(['error' => $message]);
        exit;
    }
}
