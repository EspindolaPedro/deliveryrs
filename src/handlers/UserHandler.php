<?php
namespace src\handlers;
use \src\models\Adms;

class UserHandler  {
    public static function checkLogin($token) {
        if (!empty($token)) {
          
            $data = Adms::select()->where('token', $token)->one();
          
            if (count($data) > 0) {
               $_SESSION['user'] = [
                    'name' => $data['name'],
                    'id' => $data['id'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],                   
                ];

                return $_SESSION['user'];
            }

        } 
            return false;
     
    }

    public static function verifyLogin($email, $password) {
        $user = Adms::select()->where('email', $email)->one();

        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                $token = bin2hex(random_bytes(32));
                $_SESSION['token'] = $token;                
                Adms::update()->set('token', $token)->where('email', $email)->execute();
                return $token;
            }
        } 
        return false;
    }

    
    public static function createUser($email, $password) {
        // Verifica se o email já existe
        $existingUser = Adms::select()->where('email', $email)->one();
        if ($existingUser) {
            return false; // Email já existe no banco
        }

        // Hash da senha
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Inserir o novo usuário no banco de dados
        $newUserData = [
            'name' => 'john doe',
            'email' => $email,
            'password_hash' => $passwordHash,
        ];

        // Insere no banco de dados
        $userId = Adms::insert($newUserData)->execute();
        
        if ($userId) {
            return true; 
        }

        return false; 
    }

    
}