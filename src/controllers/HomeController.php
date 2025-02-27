<?php
namespace src\controllers;

use \core\Controller;
use core\Response;

class HomeController extends Controller {

    public function indexs() {
        return Response::view('home',  ); 
    }

    public function sobre() {
        
        return Response::view('sobre'); 
    }
    public function sobreP($args) {
        print_r($args);
    }
    

}