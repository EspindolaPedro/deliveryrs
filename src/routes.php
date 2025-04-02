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
$router->get('/admin/empresa', 'AdminController@company');
$router->get('/admin/pedidos', 'AdminController@orders');


//login
$router->post('/admin', 'LoginController@LoginAction');
$router->get('/logout', 'LoginController@LogoutAction');

// Categorias
$router->post('/nova-categoria', 'CategoryController@newCategoryAction');
$router->post('/atualizar-ordem', 'CategoryController@updateOrder');
$router->post('/atualizar-categoria', 'CategoryController@updateCategory');
$router->get('/categorias', 'CategoryController@getAllCategory');

$router->get('/categorias/lista', 'CategoryController@ListCategory');

// Produtos
$router->post('/novo_produto', 'ProductController@newProduct');
$router->get('/produtos', 'ProductController@getAllProduct');
$router->post('/atualizar-produto/{id}', 'ProductController@updateProduct');
$router->delete('/deletar-produto/{id}', 'ProductController@deleteProduct');

$router->get('/produto/{value}', 'ProductController@getProduct');

// orders
$router->post('/criar-order', 'OrderController@OrderCreate');
$router->get('/pedido', 'OrderController@listOrders');


// Empresa
$router->post('/dados-empresa', 'CompanyController@insertCompanyData');
$router->post('/atualizar-horario', 'CompanyController@updateOpeningHours');
$router->post('/verificar-horario', 'CompanyController@checkIfOpen');



$router->middleware('AuthMiddleware', [
    '/admin/categoria', 
    '/admin/produtos', 
    '/admin/empresa',
    '/logout', 
    '/nova-categoria', 
    '/atualizar-ordem', 
     '/atualizar-categoria',
    '/dados-empresa',
  '/novo_produto',
  '/produtos',
  '/categorias',
  '/produto/{value}',
  '/deletar_produto/{id}',
]);

$router->middleware('LogMiddleware', ['/admin',]);
$router->middleware('CorsMiddleware', [
    '/dados-empresa', '/atualizar-categoria', '/novo-produto', '/produto/{value}',  '/categorias', '/categorias/lista'
]);

