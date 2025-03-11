<?php
use core\Router;

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
$router->post('/nova-categoria', 'CategoryController@newCategoryAction');
$router->post('/atualizar-ordem', 'CategoryController@updateOrder');
$router->post('/atualizar-categoria', 'CategoryController@updateCategory');

// Produtos
$router->post('/novo_produto', 'ProductController@newProduct');
$router->get('/produtos', 'ProductController@getAllProduct');
$router->get('/produto/{value}', 'ProductController@getProduct');
$router->put('/atualizar_produto/{id}', 'ProductController@updateProduct');
$router->delete('/deletar_produto/{id}', 'ProductController@deleteProduct');

$router->middleware('AuthMiddleware', ['/admin/categoria',]);
$router->middleware('LogMiddleware', ['/admin',]);
$router->middleware('CorsMiddleware', ['/atualizar-categoria', '/novo-produto',]);