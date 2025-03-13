<?php
namespace src\controllers;

use \core\Controller;
use core\Response;
use src\handlers\CategoryHandler;
use src\handlers\ProductHandler;

class AdminController extends Controller {

    private $categories;

    public function __construct()
    {
        $this->categories = CategoryHandler::getAllCategories();
        
    }

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

        return Response::view('category', [
            'flash' => $flash,
            'categories' => $this->categories,    
        ]);
    }

    public function product() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $products = ProductHandler::getAllProduct();

        return Response::view('product',[
            'flash' => $flash,
            'products' => $products,
            'categories' => $this->categories,    
        ]);
    }
   

    public function company() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        return Response::view('company', [
            'flash' => $flash,
        ]);
    }
   
    

}