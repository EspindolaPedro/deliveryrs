<?php
namespace src\handlers;

use src\models\Adms;
use src\models\Categories;
use Throwable;

class CategoryHandler  {
  

    public static function NameExists($name, $excludeId = null) {
        $query = Categories::select()->where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $isThere = $query->one();
        return $isThere ? true : false;
    }

    public static function newCategory($name, $is_listed) {
       

        $lastPosition = self::getLastPosition();
        $newPosition = $lastPosition + 1;
        
        Categories::insert()->values([
            'name' => $name,
            'is_listed' => $is_listed,
            'position' => $newPosition,
        ])->execute();

        return true; 
    }


    // Função para obter a última posição
    public static function getLastPosition() {
        $lastPosition = Categories::select()->max('position', 'last_position');

        return $lastPosition !== null ? (int) $lastPosition : 0;
    }

    public static function getAllCategories() {
        
        return Categories::select()
        ->orderBy('position', 'asc')
        ->execute();
         
    }

    public static function saveNewOrder($id, $position) {    
        try {
        Categories::update()->set('position', $position)->where('id', $id)->execute();    
        return true;   
    } catch (Throwable $e) {
        var_dump("Erro ao atualizar categoria: " . $e->getMessage());
        return false;
    }
    }

    public static function UpdateCategory($id, $name, $isListed) {
        try {

            $updateResult = Categories::update()
            ->set('name', $name)
            ->set('is_listed', $isListed)
            ->where('id', $id)
            ->execute();  
            
            
            return true;

        } catch (Throwable $e) {
            var_dump("Erro ao atualizar categoria: " . $e->getMessage());
            return false;
        }

    }


}
