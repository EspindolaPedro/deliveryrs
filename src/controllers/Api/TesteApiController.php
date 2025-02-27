<?php
namespace src\controllers\Api;

use core\ApiController;
use core\Cache;

class TesteApiController extends ApiController {

    public function index() {
        $cacheKey = "api_teste";
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            header("X-Cache-Hit: true");
            return $this->json($cachedData);
        }

        $data = [
            'message' => 'API funcionando!',
            'status' => 200
        ];

        Cache::set($cacheKey, $data, 900); 
        return $this->json($data);
    }
}
