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

    public static function ListCategoryHandler() {
        return Categories::select([
            'categories.id as category_id',
            'categories.position as category_position',
            'categories.name as category_name',
            'categories.is_listed as category_isListed',
            'products.id as product_id',
            'products.name as product_name',
            'products.description as product_description',
            'products.price as product_price',
            'products.price_from as product_priceFrom',
            'products.image_url as product_imageUrl',
            'products.is_listed as product_isListed',
        ])
        ->leftJoin('products', 'products.category_id', '=', 'categories.id')
        ->orderBy('categories.position')
        ->orderBy('products.created_at')
        ->get();
    }

}
