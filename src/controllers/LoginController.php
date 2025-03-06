<?php

namespace src\controllers;

use core\Controller;
use core\Request;
use core\Response;
use Exception;
use src\handlers\UserHandler;

class LoginController extends Controller
{

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
