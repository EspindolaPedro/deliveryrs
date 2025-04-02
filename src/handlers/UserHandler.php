<?php
namespace src\handlers;
use \src\models\Adms;
use \src\models\Users;

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
                    'about'  => $data['about'],           
                    'image'  => $data['image_url'],           
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
            return false; 
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

    public static function findUserByPhone($phone) {
        $isUser = Users::select()->where('phone', $phone)->first();
        if ($isUser) {
            return $isUser;
        }
        return false;
    }

    public static function createCommonUser(
        $user_name,
        $phone,
        $street,
        $number,
        $neighborhood,
        $complement
    ) {
        return Users::insert([
            'name' => $user_name,
            'phone' => $phone,
            'street' => $street,
            'number' => $number,
            'neighborhood' => $neighborhood,
            'complement' => $complement,
            'created_at' => date('Y-m-d H:i:s'),
        ])->execute();
    
    }
    


}