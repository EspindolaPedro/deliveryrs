<?php
namespace src\controllers;

use \core\Controller;

class HomeController extends Controller {

    public function index() {
       
        $this->render('home', ['hi' => 'ola funfando']);


    }

    public function sobre() {
        $this->render('sobre', ['nome' => 'Pedro']);
    }
    public function sobreP($args) {
        print_r($args);
    }
  

}