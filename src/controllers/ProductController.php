<?php
namespace src\controllers;

use \core\Controller;
use core\Response;
use LDAP\Result;
use src\handlers\ProductHandler;
use src\models\Products;

class ProductController extends Controller {
    
    public function newProduct(){

        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'];
        $price_from = $_POST['price_from'] ?? '';
        $is_listed = isset($_POST['is_listed']) ? (int)$_POST['is_listed'] : 1;
        $category_id = $_POST['category_id'];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        if(empty($name) || empty($price) || empty($category_id)){
            echo Response::json([
                'message' => 'Campos obrigatórios não preenchidos!'
            ], 400);
            exit;
        }

        $existeProduto = ProductHandler::existsnewProduct($name);

        if ($existeProduto) {
            echo Response::json([
                'message' => 'Produto já cadastrado!'
            ], 208);
            exit;
        }
        try{

            $image_url = null;
            
            if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            
                $uploadDir = __DIR__ . "/../../public/assets/images/products/";
                $tmpName = $_FILES['image_url']['tmp_name'];
                $originalName = basename($_FILES['image_url']['name']);
            
            
                // Gerar hash MD5 baseado no nome original + timestamp
                $hashName = md5($originalName . time()) . ".jpg";
                $destinationPath = $uploadDir . $hashName;
            
            
                // Comprime e move a imagem para o diretório final
                if (ProductHandler::compressImage($tmpName, $destinationPath, 70)) {
                    $image_url = "http://localhost/delivery_/assets/images/products/" . $hashName;
                } else {
                    echo Response::json([
                        'message' => 'Erro ao processar a imagem!'
                    ], 500);
                    exit;
                }
            } else {
                var_dump("Arquivo não recebido ou erro no upload!", $_FILES['image_url']['error'] ?? 'Sem arquivo');
            }


            $produto = ProductHandler::addnewProduct($name, $description, $price, $price_from, $image_url, $is_listed, $category_id, $created_at, $updated_at);
            
            if ($produto) {
                echo Response::json([
                    'message' => 'Produto cadastrado com sucesso!'
                ], 201);
                exit;
            } else {
                    echo Response::json([
                        'message' => 'Não foi possível cadastrar produto!'
                    ], 400);
                }
            }
        catch(\Exception $e){
            echo Response::json([
                'message' => 'Erro ao cadastrar produto!' . $e->getMessage()
            ], 500);
            exit;
        }
    }

    public function getAllProduct($value = null){
        try { 
            $products = ProductHandler::getAllProduct();

            if(empty($products)){
                echo Response::json([
                    'message' => 'Nenhum produto encontrado!'
                ], 404);
                exit;
            }

            echo Response::json($products);  

            }catch(\Exception $e){
                echo Response::json([
                    'message' => 'Erro ao buscar produto!' . $e->getMessage()
                ], 500);
            }
    }

    public function getProduct($value){
        try{ 
            if(is_array($value) && isset($value['value'])) {
                $value = $value['value'];
            }

            // Se buscar por "id" ou "nome" passa por aqui
            $product = ProductHandler::getProduct($value);
                
            if ($product){
                echo Response::json($product);
            } else {
                echo Response::json([
                    'message' => 'Produto não encontrado!'
                ],  404);
            }
        } catch(\Exception $e) {
            echo Response::json([
            'message' => 'Erro ao buscar produto!' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'] ?? null;
    
            $result = ProductHandler::updateProduct($id, $name, $data);
    
            switch ($result) {
                case 'not_found':
                    echo Response::json(['message' => 'Produto não encontrado!'], 404);
                    return;
                case 'name_required':
                    echo Response::json(['message' => 'O campo Nome* é obrigatório!'], 400);
                    return;
                case 'invalid_price':
                    echo Response::json(['message' => 'O campo Preço* é obrigatório e deve ser numérico!'], 400);
                    return;
                case 'name_in_use':
                    echo Response::json(['message' => 'Nome já está em uso!'], 400);
                    return;
                case 'no_changes':
                    echo Response::json(['message' => 'Nenhuma alteração foi feita!'], 400);
                    return;
                case 'update_failed':
                    echo Response::json(['message' => 'Erro ao atualizar o produto!'], 500);
                    return;
                case 'success':
                    echo Response::json(['message' => 'Produto atualizado com sucesso!', 'product' => $result], 200);
                    return;
                //Caso tenha retornado o produto de "success" mostrar o produto
                default:
                    echo Response::json(['message' => 'Produto atualizado com sucesso!', 'product' => $result], 200);
                    return;
            }
    
        } catch (\Exception $e) {
            echo Response::json(['message' => 'Erro ao processar a solicitação: ' . $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id) {
        try {
            
            $result = ProductHandler::deleteProduct($id);
    
            switch ($result) {
                case 'not_found':
                    echo Response::json(['message' => 'Produto não encontrado!'], 404);
                    return;
                case 'delete_failed':
                    echo Response::json(['message' => 'Erro ao deletar o produto!'], 500);
                    return;
                case 'success':
                    echo Response::json(['message' => 'Produto deletado com sucesso!'], 200);
                    return;
                default:
                    echo Response::json(['message' => 'Erro desconhecido!'], 500);
                    return;
            }
    
        } catch (\Exception $e) {
            // Erro inesperado
            echo Response::json(['message' => 'Erro ao processar a solicitação: ' . $e->getMessage()], 500);
        }
    }
}