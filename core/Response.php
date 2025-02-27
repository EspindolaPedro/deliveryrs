<?php
namespace core;

class Response {
    
    public static function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);

        ob_start("ob_gzhandler");
        echo json_encode($data);
        ob_end_flush();

        exit;
    }

    public static function view($viewName, $viewData = []) {
        header('Cache-Control: max-age=60, public');
<<<<<<< HEAD
        ob_start("ob_gzhandler"); 
        (new Controller())->render($viewName, $viewData);
        ob_end_flush();
    }
=======
        ob_start("ob_gzhandler");
    
        // ✅ Garante que $viewData sempre seja um array
        if (!is_array($viewData)) {
            $viewData = [];
        }
    
    
        (new Controller())->render($viewName, $viewData);
    
        ob_end_flush();
    }
    
>>>>>>> master

    public static function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}
