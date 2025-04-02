<?php
namespace src\handlers;

use core\Response;
use src\models\Categories;
use src\models\Products;

class ProductHandler  {

    
    public static function addnewProduct($name, $description, $price, $price_from, $image_url, $is_listed, $category_id, $created_at, $updated_at) {
       try{
            Products::insert([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'price_from' => $price_from,
                'image_url' => $image_url,
                'is_listed' => $is_listed,
                'category_id' => $category_id,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ])->execute();
            return true;
        } catch(\Exception $e){
                var_dump($e->getMessage());
            return false;
        }     
    }

    public static function existsnewProduct($name) {
        $isThere = Products::select()->where('name', $name)->one();
    
        if ($isThere) {
            return true;
        }
        return false;
    }

    public static function getAllProduct() {
        $products = Products::select([
            "products.id",
            "products.name",
            "products.description",
            "products.price",
            "products.price_from",
            "products.image_url",
            "products.created_at",
            "products.updated_at",
            "products.is_listed",
            "products.category_id",
            "categories.name as category_name"  // Alias correto para a categoria
        ])
        ->join("categories", "categories.id", "=", "products.category_id") // Certifique-se de que o campo category_id existe
        ->get();
        $productList = [];
        
        foreach ($products as $product) {
            $productList[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'price_from' => $product['price_from'],
                'image_url' => $product['image_url'],
                'is_listed' => $product['is_listed'],
                'category_id' => $product['category_id'],
                'category_name' => $product['category_name'], // Nome da categoria
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ];
        }
        
        return $productList;
        
    }

    public static function getProduct($value){
        $products = Products::select([
            "products.id",
            "products.name",
            "products.description",
            "products.price",
            "products.price_from",
            "products.image_url",
            "products.created_at",
            "products.updated_at",
            "products.is_listed",
            "products.category_id",
            "categories.name as category_name"  
        ])
        ->join("categories", "categories.id", "=", "products.category_id")
        ->where('products.id', $value)
        ->get();
                
        return $products;
    }

    public static function updateProduct($id, $dateToUpadte) {
        try {
            $update = Products::update($dateToUpadte)->where('id', $id);
            $result = $update->execute();
            
            return $result !== false;
            
        } catch(\Exception $e) {
            error_log("Erro ao atualizar produto {$id}: " . $e->getMessage());
            return false;
        }
    }
    public static function getProductById($id) {
        try {

            return Products::select()
                ->where('id', $id)
                ->first();

        } catch(\Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
    

    public static function deleteProduct($id) {
        
        $product = Products::select()->where('id', $id)->one();
        
        if (!$product) {
            return 'not_found';  
        }
    
        $deleted = Products::delete()->where('id', $id)->execute();
        
        if ($deleted === false) {
            return 'delete_failed'; 
        }
    
        $productAfterDelete = Products::select()->where('id', $id)->one();
    
        if (!$productAfterDelete) {
            return 'success';
        }
    }

}
