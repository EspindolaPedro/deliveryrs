<?php

namespace src\handlers;

use src\models\Adms;
use src\models\Hours;
use Throwable;

class CompanyHandler
{
    public static function updateData($name, $email, $address, $phone, $about, $image)
    {
        try {
            Adms::update([
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'about' => trim($about),
                'image_url' => $image,
                'updated_at' => date('Y-m-d H:i:s'),
            ])->execute();
            return true;
        } catch (Throwable $e) {
            var_dump("Erro ao atualizar categoria: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllData()
    {
        $data = Adms::select('*')->one();

        if (count($data) > 0) {
            $data = [
                'id' => $data['id'],
                'name' => $data['name'],
                'about' => $data['about'],
                'email' => $data['email'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'image_url' => $data['image_url'],
            ];

            return $data;
        }
    }



    public static function isOpen($day, $currentTime) {

    }
    
    public static function getOpeningHours() {
        return Hours::select()->execute();
    }

    public static function updateOpeningHours($id, $data) {
        return Hours::update()->set($data)->where('id', $id)->execute();
    }
}
