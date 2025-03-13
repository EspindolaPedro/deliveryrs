<?php
namespace src;

class Config {
    const BASE_DIR = '/deliveryrs/public';
    const IMAGE_DIR = 'http://localhost/deliveryrs/public';

    const DB_DRIVER = 'mysql';
    const DB_HOST = '127.0.0.1'; 
    const DB_DATABASE = 'delivery'; 
    const DB_USER = 'root'; 
    const DB_PASS = ''; 

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';
}
