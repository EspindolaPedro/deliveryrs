<?php
namespace src\handlers;

use core\Response;
use src\models\Products;

class ProductHandler  {

    //Adiciona o produto no DB
    
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
        $products = Products::select()->get();
        

        $productList = [];

        foreach ($products as $product){
            $productList[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'price_from' => $product['price_from'],
                'image_url' => $product['image_url'],
                'is_listed' => $product['is_listed'],
                'category_id' => $product['category_id'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ];
        }
        return $productList;

    }

    public static function getProduct($value){
  
        if(empty($value)){
            return null;
        }
        
        $query = Products::select();
        if (ctype_digit($value)){
            $query->where('id', (int)$value);
        }else{
            $query->where('name', $value);
        }
        $product = $query->one();
        
        if($product){
            $productData = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'price_from' => $product['price_from'],
                'image_url' => $product['image_url'],
                'is_listed' => $product['is_listed'],
                'category_id' => $product['category_id'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ];
            return $productData ?: null;
        }
    }

    public static function updateProduct($id, $name, $price, $price_from, $image_url, $data) {
        $product = Products::select()->where('id', $id)->one();
    
        if (!$product) {
            return 'not_found'; 
        }
    
        // Validação de nome
        if (isset($data['name']) && trim($data['name']) === '') {
            return 'name_required'; 
        }
    
        // Validação de preço
        if (isset($data['price']) && !is_numeric($data['price'])) {
            return 'invalid_price'; 
        }
    
        // Validação de price_from
        if (isset($data['price_from']) && !is_numeric($data['price_from'])) {
            return 'invalid_price_from'; 
        }
    
        $updateData = array_diff_key($data, ['id' => '', 'created_at' => '']);
    
        if (empty($updateData)) {
            return 'no_changes'; 
        }
    
        // Verifica se o nome foi alterado
        if ($name && $name !== $product['name']) {
            $existingProduct = Products::select()->where('name', $name)->where('id', '!=', $id)->one();
            if ($existingProduct) {
                return 'name_in_use'; // Nome já está em uso
            }
        }
    
        // Verifica se o preço foi alterado, e se sim, remove o campo price da atualização se não houver alteração
        if (isset($updateData['price']) && $updateData['price'] == $product['price']) {
            unset($updateData['price']);
        }
    
        // Verifica se há mudanças reais nos dados antes de adicionar o updated_at
        $changes = array_diff_assoc($updateData, $product);
    
        if (empty($changes)) {
            return 'no_changes'; // Nenhuma alteração foi feita
        }
    
        // Se houver nova imagem
        if ($image_url) {
            $updateData['image_url'] = $image_url;
        }
    
        // Adiciona o updated_at
        $updateData['updated_at'] = date('Y-m-d H:i:s');
    
        // Realiza a atualização no banco de dados
        $updated = Products::update($updateData)->where('id', $id)->execute();
    
        if ($updated === false || $updated === 0) {
            return 'update_failed'; // Falha na atualização
        }
    
        // Retorna o produto atualizado
        $updatedProduct = Products::select()->where('id', $id)->one();
        return $updatedProduct;
    }
    
    

    public static function deleteProduct($id) {
        // 1. Verificar se o produto existe
        $product = Products::select()->where('id', $id)->one();
        
        if (!$product) {
            return 'not_found';  // Produto não existe
        }
    
        // 2. Tentar excluir o produto
        $deleted = Products::delete()->where('id', $id)->execute();
        
        if ($deleted === false) {
            return 'delete_failed'; // Falha na execução
        }
    
        // 3. Confirmar se o produto foi excluído
        $productAfterDelete = Products::select()->where('id', $id)->one();
    
        // 4. Se o produto não existe mais após a exclusão, a exclusão foi bem-sucedida
        if (!$productAfterDelete) {
            return 'success';
        }
    }

    public static function compressImage($source, $destination, $quality) {
        $info = getimagesize($source);
    
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
            imagepalettetotruecolor($image);
        } elseif ($info['mime'] == 'image/webp') {
            $image = imagecreatefromwebp($source);
        } else {
            return false;
        }
    
        imagejpeg($image, $destination, $quality);
        imagedestroy($image);
    
        return true;
    }
}