<?php
use core\Router;
use src\controllers\AdminController;

$router = new Router();

// $router->get('/', 'HomeController@indexs');
// $router->get('/sobre/{nome}', 'HomeController@sobreP');
// $router->get('/sobre', 'HomeController@sobre');

// view
$router->get('/admin', 'AdminController@index');
$router->get('/admin/categoria', 'AdminController@category');
$router->get('/admin/produtos', 'AdminController@product');


//login
$router->post('/admin', 'LoginController@LoginAction');
$router->post('/logout', 'LoginController@LogoutAction');

// Categorias
$router->post('/nova-categoria', 'CategoryActionsController@newCategoryAction');
$router->post('/atualizar-ordem', 'CategoryController@updateOrder');
$router->post('/atualizar-categoria', 'CategoryController@updateCategory');


$router->middleware('AuthMiddleware', ['/admin/categoria',]);
$router->middleware('LogMiddleware', ['/admin',]);

$router->middleware('CorsMiddleware', ['/atualizar-categoria',]);
