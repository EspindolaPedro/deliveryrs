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

    public static function updateProduct($id, $name, $price, $price_from, $image_url, $data) {
    $product = Products::select()->where('id', $id)->one();
    if (!$product) return 'not_found';

    // Validação: se nome veio, não pode ser vazio
    if (isset($data['name']) && trim($data['name']) === '') {
        return 'name_required';
    }

    // Validação: nome em uso por outro produto
    if (isset($data['name']) && $data['name'] !== $product['name']) {
        $existing = Products::select()
            ->where('name', $data['name'])
            ->where('id', '!=', $id)
            ->one();
        if ($existing) return 'name_in_use';
    }

    // Validações de preço (somente se enviados)
    if (isset($price) && !is_numeric($price)) return 'invalid_price';
    if (isset($price_from) && !is_numeric($price_from)) return 'invalid_price_from';

    // Monta dados para update somente se forem diferentes dos atuais
    $updateData = [];

    if ($name !== null && $name !== $product['name']) {
        $updateData['name'] = $name;
    }

    if ($price !== null && (float)$price != (float)$product['price']) {
        $updateData['price'] = $price;
    }

    if ($price_from !== null && (float)$price_from != (float)$product['price_from']) {
        $updateData['price_from'] = $price_from;
    }

    if (isset($data['description']) && $data['description'] !== $product['description']) {
        $updateData['description'] = $data['description'];
    }

    if (isset($data['is_listed']) && (int)$data['is_listed'] !== (int)$product['is_listed']) {
        $updateData['is_listed'] = (int)$data['is_listed'];
    }

    if (isset($data['category_id']) && (int)$data['category_id'] !== (int)$product['category_id']) {
        $updateData['category_id'] = (int)$data['category_id'];
    }

    if ($image_url && $image_url !== $product['image_url']) {
        $updateData['image_url'] = $image_url;
    }

    if (empty($updateData)) return 'no_changes';

    $updateData['updated_at'] = date('Y-m-d H:i:s');

    $updated = Products::update($updateData)->where('id', $id)->execute();
    if (!$updated) return 'update_failed';

    return Products::select()->where('id', $id)->one();
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
