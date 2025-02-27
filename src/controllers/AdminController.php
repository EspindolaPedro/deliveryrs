<?php
namespace src\controllers;

use \core\Controller;
use core\Response;

class AdminController extends Controller {

    public function index() {
        
        return Response::view('admin', ); 
    }

   
    

}