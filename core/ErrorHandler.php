<?php
namespace core;

class ErrorHandler {
    public static function handleError($errno, $errstr, $errfile, $errline) {
        http_response_code(500);

        $errorMessage = "Erro: [$errno] $errstr - Arquivo: $errfile - Linha: $errline";

        // Se for uma requisição API, retorna JSON
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            echo json_encode(["error" => "Erro interno no servidor"]);
        } else {
            echo $errorMessage;
        }

        exit;
    }

    public static function handleException($exception) {
        http_response_code(500);

        $errorMessage = "Exceção: " . $exception->getMessage();

        // Se for uma requisição API, retorna JSON
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            echo json_encode(["error" => "Erro interno no servidor"]);
        } else {
            echo $errorMessage;
        }

        exit;
    }
}

// Ativa os handlers
set_error_handler(['core\ErrorHandler', 'handleError']);
set_exception_handler(['core\ErrorHandler', 'handleException']);
