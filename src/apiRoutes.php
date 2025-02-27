<?php
global $router;

$router->middleware('CorsMiddleware', ['/api/*']);

$router->get('/api/teste', 'TesteApiController@index');
$router->get('/api/users', 'TesteApiController@users');

$router->middleware('TestMiddleware', ['/api/protegido']);
$router->get('/api/protegido', 'TesteApiController@users');