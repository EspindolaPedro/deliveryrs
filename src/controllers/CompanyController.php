<?php

namespace src\controllers;
use core\Controller;
use src\controllers\ProductController;
use core\Response;
use src\handlers\CompanyHandler;
use Throwable;

class CompanyController extends Controller
{   
    public function insertCompanyData() {
        $name = $_POST['name'];
        $email = $_POST['email'] ?? "";
        $address = $_POST['address'] ?? "";
        $phone = $_POST['phone'] ?? "";
        $about = $_POST['about'] ?? "";

        try {
            $currentData = CompanyHandler::getAllData(); 
            $currentImage = $currentData['image_url'] ?? null;
    
            if (!empty($_FILES['image_url']['tmp_name'])) {
                $productController = new ProductController();
                $image = $productController->handleImageUpload(); 
            } else {
                $image = $currentImage; 
            }
          
            $data = CompanyHandler::updateData($name, $email, $address, $phone, $about, $image);
            if ($data) {
                echo $_SESSION['flash'] = 'Dados cadastrados!';
                $this->redirect('/admin/empresa');
            exit;
            } 
        } catch (Throwable $e) {
            echo $_SESSION['flash'] = "Erro ao cadastrar os dados!".$e->getMessage();
            $this->redirect('/admin/empresa');
            exit;
        }        

        
    }      

    public function updateOpeningHours() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];

        $result = CompanyHandler::updateOpeningHours($id, $data);

        if ($result) {
            echo Response::json(["message" => "Horários atualizados com sucesso!"], 201);
        }
        return Response::json(["message" => "Erro ao atualizar os horários"], 401);
    }

    public function checkIfOpen() {
        $data = json_decode(file_get_contents('php://input'), true);
        $day  = $data['day'];
        $currentTime = date('H:i:s');

        
        $isOpen = CompanyHandler::isOpen($day, $currentTime);

        echo Response::json(["is_open" => $isOpen]);

    }

    public function getData() {
        
        $data = CompanyHandler::getAllData();
        echo Response::json($data);
    }

} 
