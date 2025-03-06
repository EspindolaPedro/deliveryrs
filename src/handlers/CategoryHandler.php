<?php
namespace src\handlers;

use src\models\Categories;
use Throwable;

class CategoryHandler  {
  

    public static function newCategory($name, $is_listed) {

        $isThere = Categories::select()->where('name', $name)->one();

        if ($isThere) {
            return false; 
        }

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

    public static function updateCategoriesOrder($novasOrdens) {
        
    }

    public static function getAllCategories() {
        return Categories::select()
        ->orderBy('position', 'asc')
        ->execute();

    }

    public static function saveNewOrder($id, $position) {        
        Categories::update()->set('position', $position)->where('id', $id)->execute();       
    }

    public static function UpdateCategory($id, $name, $isListed) {
        $updateResult = Categories::update()
        ->set('name', $name)
        ->set('is_listed', $isListed)
        ->where('id', $id)
        ->execute();  
        return $updateResult > 0;

    }

}
