<?php
global $router;


// $router->get('/api/teste', 'TesteApiController@index');
// $router->get('/api/users', 'TesteApiController@users');

// $router->get('/api/protegido', 'TesteApiController@users');
// $router->middleware('TestMiddleware', ['/api/protegido']);

//$router->post('/api/signin', 'AdmActionsController@LoginAction');

$router->post('/api/register', 'AdmActionsController@RegisterAction');


$router->middleware('CorsMiddleware', ['/api/signin']);