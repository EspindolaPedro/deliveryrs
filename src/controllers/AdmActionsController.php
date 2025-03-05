<?php

namespace src\controllers;

use core\Controller;
use core\Request;
use core\Response;
use Exception;
use src\handlers\CategoryHandler;
use src\handlers\UserHandler;
use src\models\Categories;
use Throwable;

class AdmActionsController extends Controller
{

    public function newCategoryAction()
    {

        $categoryName = filter_input(INPUT_POST, 'nameCategory');
        $is_listed = isset($_POST['is_listed']) ? 1 : 0;

        if (empty($categoryName)) {
            $this->redirect('/admin/categoria');
            exit;
        }
        try {
            $category = CategoryHandler::newCategory($categoryName, $is_listed);

            if (!$category) {
                $_SESSION['flash'] = 'Categoria já cadastrada!';
                $this->redirect('/admin/categoria');
                exit;
            }

            $_SESSION['flash'] = 'Categoria cadastrada com sucesso!';
            $this->redirect('/admin/categoria');
            exit;
        } catch (Exception $e) {
            $_SESSION['flash'] = "Erro ao criar categoria: " . $e->getMessage();
            $this->redirect('/admin/categoria');
            exit;
        }
    }

    public function newOrderList() {
        if (isset($_POST['order']) && is_array($_POST['order'])) {
            $novasOrdens = $_POST['ordem'];

            CategoryHandler::updateCategoriesOrder($novasOrdens);
        }
    }

    public function updateOrder($req) {
        $json = json_decode(file_get_contents('php://input'), true);

        if (isset($json['order']) && is_array($json['order'])) {
            foreach ($json['order'] as $item) {
                $id = intval($item['id']);
                $position = intval($item['position']);

                CategoryHandler::saveNewOrder($id, $position);
            }

            echo json_encode(['message' => 'Ordenação salva com sucesso!']);
            exit;
        }
        
        echo json_encode(['erro' => 'Erro ao atualizar a ordenação']);
        exit;
    }
  
    public function updateCategory() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $name = $data['name'];
        $isListed = $data['isListed'];
    
        if (!$id || !$name) {
            return json_encode(["error" => true, "message" => "ID ou Nome inválido."]);
        }
        try {
            $updatedCategory = CategoryHandler::UpdateCategory($id, $name, $isListed);
        } catch (Exception $e) {
            return json_encode(["message" => "$e"]);
        }
    
        if ($updatedCategory) {
            return json_encode(["success" => true, "message" => "Categoria atualizada."]);
        }
    
        return json_encode(["error" => true, "message" => "Erro ao atualizar a categoria"]);
    }

    public function LoginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if (!$email && !$password) {
            $_SESSION['flash'] = 'Todos os dados devem estar preenchidos!';
            $this->redirect('/admin');
            exit;
        }

        $token = UserHandler::verifyLogin($email, $password);

        if (!$token) {
            $_SESSION['flash'] = 'Usuario não cadastrado ou dados incorretos.';
            $this->redirect('/admin');
            exit;
        }

        UserHandler::checkLogin($token);
        $this->redirect('/admin/categoria');
        exit;
    }

    public function RegisterAction()
    {
        if (Request::getMethod() !== 'post') {
            return Response::json(["error" => "Método não permitido"], 405);
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if (!$email || !$password) {
            return Response::json(["error" => "Todos os campos precisam ser preenchidos!"], 400);
        }

        // Chama o método createUser do UserHandler
        $userCreated = UserHandler::createUser($email, $password);

        if ($userCreated) {
            return Response::json(["success" => true, "message" => "Usuário criado com sucesso!"], 201);
        } else {
            return Response::json(["error" => "Falha ao criar usuário ou email já existente"], 400);
        }
    }


    public function LogoutAction()
    {

        session_destroy();
        $this->redirect("/admin");
        exit;
    }
}
