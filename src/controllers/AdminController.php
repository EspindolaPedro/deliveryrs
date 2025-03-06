<?php
namespace src\controllers;

use \core\Controller;
use core\Response;
use src\handlers\CategoryHandler;

class AdminController extends Controller {

    public function index() {                   
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        return Response::view('login', ['flash' => $flash] ); 
    }
 
    public function category() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $categories = CategoryHandler::getAllCategories();

        return Response::view('category', [
            'flash' => $flash,
            'categories' => $categories,    
        ]);
    }

    public function product() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        return Response::view('product', [
            'flash' => $flash,
        ]);
    }
   
    

}