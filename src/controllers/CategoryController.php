<?php

namespace src\controllers;

use core\Controller;
use core\Response;
use Exception;
use src\handlers\CategoryHandler;
use src\models\Categories;
use Throwable;

class CategoryController extends Controller
{   

    public function newCategoryAction()
    {

        $categoryName = filter_input(INPUT_POST, 'nameCategory');
        $is_listed = isset($_POST['is_listed']) ? 1 : 0;
        
        if (empty($categoryName)) {
            $this->redirect('/admin/categoria');
            exit;
        }      

        $nameExists = CategoryHandler::NameExists($categoryName);

        if ($nameExists) {
            $_SESSION['flash'] = 'Está categoria já existe!';
            $this->redirect('/admin/categoria');
            exit;
        }

        try {
            $category = CategoryHandler::newCategory($categoryName, $is_listed);

            $_SESSION['flash'] = 'Categoria cadastrada com sucesso!';
            $this->redirect('/admin/categoria');
            exit;
        } catch (Exception $e) {
            $_SESSION['flash'] = "Erro ao criar categoria: " . $e->getMessage();
            $this->redirect('/admin/categoria');
            exit;
        }
    }

    public function getAllCategory() {
        try {

            $categories = CategoryHandler::getAllCategories();

            $data = [];
            foreach($categories as $category) {
                $data[] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'is_listed' => $category['is_listed'],
                    'position' => $category['position'],
                ];
            }
            echo Response::json($data);            
            exit;

            

        } catch (Throwable $e) {
            echo Response::json(['error' => 'Erro ao buscar categorias.'], 500);
            exit;
        }
    }

    public function updateOrder() {
        $json = json_decode(file_get_contents('php://input'), true);

        if (isset($json['order']) && is_array($json['order'])) {
            foreach ($json['order'] as $item) {
                $id = intval($item['id']);
                $position = intval($item['position']);

                CategoryHandler::saveNewOrder($id, $position);
            }

            echo Response::json(['message' => 'Ordenação salva com sucesso!'],200);
            exit;
        }
        
        echo Response::json(["message" =>'Erro ao atualizar a ordenação'], 401);
        exit;
    }
  
    public function updateCategory() {
        /**
         * Recebe dados via POST (body) em json e transforma em uma array associativa.
         */
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $name = $data['name'];
        $isListed = $data['isListed'];
    
        if (!$id || !$name) {
            echo Response::json(["message" => "ID ou Nome inválido."], 401);
            exit;
        }

        $nameExists = CategoryHandler::NameExists($name, $id);

        if ($nameExists) {
            echo Response::json(["message" => "Está categoria já existe"], 401);
            exit;
        }
        
        try {
            $updatedCategory = CategoryHandler::UpdateCategory($id, $name, $isListed);
            if ($updatedCategory) {
                echo Response::json(["message" => "Categoria atualizada."]);
                exit;
            }
            else {
                echo Response::json(["message" => "Está categoria já existe."], 401);
                exit;
            }
        } catch (Exception $e) {
            echo Response::json(["message" => "$e"], 401);
            exit;
        }
              
    }

    public function ListCategory() {

        try {
            
            $list = CategoryHandler::ListCategoryHandler();
    
            $result = [];
    
            foreach($list as $row) {
                $categoryId = $row['category_id'];

                if (!isset($result[$categoryId])) {
                    $result[$categoryId] = [
                        'id' => $row['category_id'],
                        'nome' => $row['category_name'],
                        'is_listed' => $row['category_isListed'],
                        'position' => $row['category_position'],
                        'products' => []
                    ];
                }
                if ($row['product_id']) {
                    $result[$categoryId]['products'][] = [
                        'id' => $row['product_id'],
                        'name' => $row['product_name'],
                        'description' => $row['product_description'],
                        'is_listed' => $row['product_isListed'],
                        'image_url' => $row['product_imageUrl'],
                        'price' => $row['product_price'],
                        'price_from' => $row['product_priceFrom'],
                    ];
                }
            }
            $result = array_filter($result, function($category) {
                return !empty($category['products']); // ou $category['product_count'] 
            });
            $result = array_values($result);

            echo Response::json($result, 200);
            exit;
        } catch (Exception $e) {
            echo Response::json(["message" => "Erro" . $e->getMessage()]);
        }


    }

} 
