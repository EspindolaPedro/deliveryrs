<?php
namespace core;

use \src\Config;
use core\Cache;

class Controller {

    protected function redirect($url) {
        header("Location: ".$this->getBaseUrl().$url);
        exit;
    }

    public function getBaseUrl() {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':'.$_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;
        
        return $base;
    } // $cacheKey = "view_" . md5($folder . $viewName . json_encode($viewData));
    // $cachedHtml = Cache::get($cacheKey);

    // if ($cachedHtml) {
    //     echo $cachedHtml; 
    //     return;
    // }

   private function _render($folder, $viewName, $viewData = []) {
    $file = "../src/views/{$folder}/{$viewName}.php";

    if (file_exists($file)) {
        $viewData['base'] = $this->getBaseUrl(); 
        extract($viewData);
        $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
     

        ob_start();
        require $file;
        $html = ob_get_clean();
        echo $html;
    } else {
        die("Erro: A view <strong>{$folder}/{$viewName}.php</strong> não foi encontrada.");
    }
}


    private function renderPartial($viewName, $viewData = []) {
        $this->_render('partials', $viewName, $viewData);
    }

    public function render($viewName, $viewData = []) {
        $this->_render('pages', $viewName, $viewData);
    }

    public function clearViewCache($viewName, $viewData = []) {
        $cacheKey = "view_" . md5('pages' . $viewName . json_encode($viewData));
       // Cache::clear($cacheKey);
    }
}