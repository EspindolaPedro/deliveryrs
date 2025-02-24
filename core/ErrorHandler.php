<?php
namespace core;

use core\Logger;

class ErrorHandler {
    public static function handle($errno, $errstr, $errfile, $errline) {
        http_response_code(500);

        $errorMessage = "Erro: [$errno] $errstr - Arquivo: $errfile - Linha: $errline";
        Logger::log($errorMessage); // Salva o erro no log

        // Se for uma requisição API, retorna JSON
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            echo json_encode(["error" => "Erro interno no servidor"]);
        } else {
            // Se for uma página, mostra um erro amigável
            echo "<h1>Ops! Algo deu errado.</h1>";
            echo "<p>Estamos verificando esse erro, tente novamente mais tarde.</p>";
        }

        exit;
    }
}

//  Ativa o tratamento de erros
set_error_handler(['core\ErrorHandler', 'handle']);
set_exception_handler(['core\ErrorHandler', 'handle']);
