<?php
use core\Router;

$router = new Router();

// $router->get('/', 'HomeController@indexs');
// $router->get('/sobre/{nome}', 'HomeController@sobreP');
// $router->get('/sobre', 'HomeController@sobre');

// view
$router->get('/admin', 'AdminController@index');
$router->get('/admin/categoria', 'AdminController@category');


$router->post('/admin', 'AdmActionsController@LoginAction');
$router->post('/logout', 'AdmActionsController@LogoutAction');

// Categorias
$router->post('/nova-categoria', 'AdmActionsController@newCategoryAction');
$router->post('/atualizar-ordem', 'AdmActionsController@updateOrder');
$router->post('/atualizar-categoria', 'AdmActionsController@updateCategory');




$router->middleware('AuthMiddleware', ['/admin/categoria',]);
$router->middleware('LogMiddleware', ['/admin',]);
