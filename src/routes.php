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


//login
$router->post('/admin', 'LoginController@LoginAction');
$router->get('/logout', 'LoginController@LogoutAction');

// Categorias
$router->post('/nova-categoria', 'CategoryController@newCategoryAction');
$router->post('/atualizar-ordem', 'CategoryController@updateOrder');
$router->post('/atualizar-categoria', 'CategoryController@updateCategory');

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
    // '/atualizar-categoria',
    '/dados-empresa',
]);

$router->middleware('LogMiddleware', ['/admin',]);
$router->middleware('CorsMiddleware', [
    '/dados-empresa', 
]);
