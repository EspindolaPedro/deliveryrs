<?php

namespace src\controllers;

use \core\Controller;
use core\Response;
use Exception;
use src\handlers\ProductHandler;
use \src\Config;

class ProductController extends Controller
{

    public function newProduct()
    {
        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? null;
        $price_from = $_POST['price_from'];
        $is_listed = isset($_POST['is_listed']) ? 1 : 0;
        $category_id = $_POST['category_id'];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        if (empty($name) || empty($price) || empty($category_id)) {
            $_SESSION['flash'] = "Dados não preenchidos corretamente!";
            $this->redirect('/admin/produtos');
            exit;
        }

        // Validação de preço com regex
        $regex = '/^\d{1,3}(\.\d{3})*,\d{2}$/';

        if ((!preg_match($regex, $price) && !empty($price)) || (!preg_match($regex, $price_from) && !empty($price_from))) {
            $_SESSION['flash'] = "Dados não preenchidos corretamente!";
            $this->redirect('/admin/produtos');
            exit;
        }

        // Conversão dos preços
        if (!empty($price_from)) {
            $price_from = $this->convertPrice($price_from);
        }
        if (!empty($price)) {
            $price = $this->convertPrice($price);
        }

        // Verifica se o produto já existe
        if (ProductHandler::existsnewProduct($name)) {
            $_SESSION['flash'] = "Produto já cadastrado!";
            $this->redirect('/admin/produtos');
            exit;
        }

        try {
            // Upload da imagem
            $image_url = null;
            if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
                $image_url = $this->handleImageUpload();
                if (!$image_url) {
                    $_SESSION['flash'] = "Erro ao cadastrar imagem!";
                    $this->redirect('/admin/produtos');
                    exit;
                }
            }
            // Adiciona o produto no banco 
            $produto = ProductHandler::addnewProduct($name, $description, $price, $price_from ? $price_from : null, $image_url, $is_listed, $category_id, $created_at, $updated_at);

            if ($produto) {
                $_SESSION['flash'] = "Produto cadastrado com sucesso!";
            } else {
                $_SESSION['flash'] = "Não foi possível cadastrar o produto!";
            }

            $this->redirect('/admin/produtos');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash'] = "Erro ao cadastrar o produto: " . $e->getMessage();
            $this->redirect('/admin/produtos');
            exit;
        }
    }


    public function getAllProduct()
    {
        try {
            $products = ProductHandler::getAllProduct();

            if (empty($products)) {

                echo Response::json([
                    'message' => 'Produto não encontrado!'
                ], 500);
                exit;
            }

            echo Response::json($products);
        } catch (\Exception $e) {
            echo Response::json([
                'message' => 'Erro ao buscar produto!' . $e->getMessage()
            ], 500);
        }
    }

    public function getProduct($value)
    {
        try {
            // Se buscar por "id" ou "nome" passa por aqui
            $product = ProductHandler::getProduct($value);

            if ($product) {
                echo Response::json($product, 200);
            } else {
                echo Response::json([
                    'message' => 'Produto não encontrado!'
                ],  404);
            }
        } catch (\Exception $e) {
            echo Response::json([
                'message' => 'Erro ao buscar produto!' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct($id)
    {
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $price_from = $_POST['price_from'] ?? '';
        $is_listed = isset($_POST['is_listed']) ? 1 : 0;
        $category_id = $_POST['category_id'] ?? '';
        $updated_at = date('Y-m-d H:i:s');

        $product = ProductHandler::getProductById($id);
        $dateToUpadte = [];

        $regex = '/^\d{1,3}(\.\d{3})*(,\d{2})?$/';

        if ((!preg_match($regex, $price) && !empty($price)) || (!preg_match($regex, $price_from) && !empty($price_from))) {
            Response::json(["message" => "Dados não preenchidos corretamente!"]);
            exit;
        }

        // Conversão dos preços
        if (!empty($price_from)) {
            $price_from = $this->convertPrice($price_from);
        }
        if (!empty($price)) {
            $price = $this->convertPrice($price);
        }

        if ($price !== $product['price']) {
            $dateToUpadte['price'] = $price;
        }
        if ($price_from !== $product['price_from']) {
            $dateToUpadte['price_from'] = $price_from;
        }
        if ($name !== $product['name']) {
            $dateToUpadte['name'] = $name;
        }

        if ($description !== $product['description']) {
            $dateToUpadte['description'] = $description;
        }

        if ($is_listed !== $product['is_listed']) {
            $dateToUpadte['is_listed'] = $is_listed;
        }

        if ($category_id !== $product['category_id']) {
            $dateToUpadte['category_id'] = $category_id;
        }

        if (!empty($_FILES['image_url']['tmp_name'])) {
            $nova_imagem = $this->handleImageUpload();
            $dateToUpadte['image_url'] = $nova_imagem;
        }

        if (empty($dateToUpadte)) {
            echo Response::json(['message' => 'Nenhum dado enviado.'], 200);
            exit;
        }

        $dateToUpadte['updated_at'] = $updated_at;

        try {
            $res = ProductHandler::updateProduct($id, $dateToUpadte);
            if ($res) {
                echo Response::json(['message' => 'Atualizado com sucesso', 'Dados atualizados: ' => json_encode($dateToUpadte)], 201);
                exit;
            }
        } catch (Exception $e) {
            echo Response::json(["message" => "Erro para atualizar" . $e->getMessage()], 401);
            exit;
        }
    }

    public function deleteProduct($id)
    {
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


    private function convertPrice($price)
    {
        $formattedPrice = str_replace('.', '', $price);
        $formattedPrice = str_replace(',', '.', $formattedPrice);
        return (float)$formattedPrice;
    }

    private function handleImageUpload()
    {
        $uploadDir = __DIR__ . "/../../public/assets/images/products/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tmpName = $_FILES['image_url']['tmp_name'];
        $originalName = basename($_FILES['image_url']['name']);
        $hashName = $originalName . uniqid() . ".jpg";
        $destinationPath = $uploadDir . $hashName;

        // Comprime e move a imagem para o diretório final
        if ($this->compressImage($tmpName, $destinationPath, 70)) {
            return Config::IMAGE_DIR . "/assets/images/products/" . $hashName;
        } else {
            return false;
        }
    }

    public static function compressImage($source, $destination, $quality)
    {
        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') {
            $image = @imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = @imagecreatefrompng($source);
            imagepalettetotruecolor($image);
        } elseif ($info['mime'] == 'image/webp') {
            $image = @imagecreatefromwebp($source);
        } else {
            return false;
        }

        imagejpeg($image, $destination, $quality);
        imagedestroy($image);

        return true;
    }
}
