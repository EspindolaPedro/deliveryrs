<?php
namespace src\middlewares;

use core\Middleware;

class LogMiddleware extends Middleware {
    public function handle($request, $next) {
        $logFile = 'logs/access.log';

        // Cria a pasta logs se não existir
        if (!is_dir('logs')) {
            mkdir('logs', 0777, true);
        }

        // Formata a mensagem de log
        $logMessage = sprintf(
            "[%s] - %s - %s\n",
            date('Y-m-d H:i:s'),
            $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN_IP',
            $request
        );

        // Escreve no arquivo de log
        file_put_contents($logFile, $logMessage, FILE_APPEND);

        // Continua para o próximo middleware ou controlador
        return $next();
    }
}
