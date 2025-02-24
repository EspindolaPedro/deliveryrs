<?php
namespace core;

use \src\Config;
use core\Cache;

class Controller {

    protected function redirect($url) {
        header("Location: ".$this->getBaseUrl().$url);
        exit;
    }

    private function getBaseUrl() {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':'.$_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;
        
        return $base;
    }

    private function _render($folder, $viewName, $viewData = []) {
        $cacheKey = "view_" . md5($folder . $viewName . json_encode($viewData));
        $cachedHtml = Cache::get($cacheKey);

        if ($cachedHtml) {
            echo $cachedHtml; 
            return;
        }

        if(file_exists('../src/views/'.$folder.'/'.$viewName.'.php')) {
            extract($viewData);
            $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();

            ob_start();
            require '../src/views/'.$folder.'/'.$viewName.'.php';
            $html = ob_get_clean();
            Cache::set($cacheKey, $html, 1800);
            echo $html;
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
        Cache::clear($cacheKey);
    }
}