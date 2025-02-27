<?php
use core\Router;

$router = new Router();

// $router->get('/', 'HomeController@indexs');
// $router->get('/sobre/{nome}', 'HomeController@sobreP');
// $router->get('/sobre', 'HomeController@sobre');


$router->get('/admin', 'AdminController@index');


$router->middleware('LogMiddleware', ['/admin',]);
