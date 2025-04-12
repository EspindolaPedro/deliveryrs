<?php

namespace src\controllers;

use core\Controller;
use core\Response;
use Exception;
use src\handlers\OrderHandler;

class OrderController extends Controller
{   
    public function OrderCreate() 
    {   $json = file_get_contents('php://input');
        $data = json_decode($json, true);   

        if (!$data) {
            echo json_encode(["status" => "error", "message" => "JSON inválido ou corpo vazio"]);
            exit;
        }

        $result = OrderHandler::createOrder($data);
        $result = array_values($result);
        if ($result) {
            echo Response::json(
                $result
        , 201);
            exit;
        }

        echo Response::json(['message' => $result['message']]);
        exit;
     

    }
  
    public function listOrders() {

        try {

            $Orderlist = OrderHandler::listOrder();

            $orders = [];

            foreach ($Orderlist as $row) {
                $orderId = $row['id'];

                if (!isset($orders[$orderId])) {

                    $orders[$orderId] = [
                        "id" => $row["id"],
                        "status" => $row["order_status"],
                        "observation" => $row["order_observation"],
                        "payment_method" => $row["payment_method"],
                        "user_id" => $row["user_id"],
                        "created_at" => $row["created_at"],
                        "updated_at"=>$row["updated_at"],
                        "subtotal"=>$row["subtotal"],
                        "user" => [
                            "name" => $row["user_name"],
                            "phone" => $row["phone"],
                            "street" => $row["street"],
                            "number" => $row["number"],
                            "neighborhood" => $row["neighborhood"],
                            "complement" => $row["complement"],
                        ],
                        "items" => [],
                    ];
                }

                $orders[$orderId]["items"][] = [
                    "id" => $row["item_id"],
                    "amount" => $row["amount"],
                    "observation" => $row["item_observation"],
                    "product" => [
                        "id" => $row["product_id"],
                        "name" => $row["product_name"],
                        "price" => $row["product_price"],
                    ]
                    ];

            }

            echo Response::json(array_values($orders), 200);
            exit;
        } catch (Exception $e) {
            echo Response::json(["message" => "Erro $e"], 500);
            exit;
        }

    }

    public function getOrderById($pedido) {
        $id = $pedido['id'] ?? null;
    
        if (!$id) {
            echo Response::json(["message" => "ID do pedido não informado"], 400);
            return;
        }
    
        try {
            $orderData = OrderHandler::getOrderById($id);
    
            if (empty($orderData)) {
                echo Response::json(["message" => "Pedido não encontrado"], 404);
                return;
            }
    
            $order = [];
            foreach ($orderData as $row) {
                $orderId = $row['id'];
    
                if (empty($order)) {
                    $order = [
                        "id" => $row["id"],
                        "status" => $row["order_status"],
                        "observation" => $row["order_observation"],
                        "payment_method" => $row["payment_method"],
                        "user_id" => $row["user_id"],
                        "created_at" => $row["created_at"],
                        "updated_at" => $row["updated_at"],
                        "subtotal" => $row["subtotal"],
                        "user" => [
                            "name" => $row["user_name"],
                            "phone" => $row["phone"],
                            "street" => $row["street"],
                            "number" => $row["number"],
                            "neighborhood" => $row["neighborhood"],
                            "complement" => $row["complement"],
                        ],
                        "items" => [],
                    ];
                }
    
                $order["items"][] = [
                    "id" => $row["item_id"],
                    "amount" => $row["amount"],
                    "observation" => $row["item_observation"],
                    "product" => [
                        "id" => $row["product_id"],
                        "name" => $row["product_name"],
                        "price" => $row["product_price"],
                    ]
                ];
            }
    
            echo Response::json([$order], 200);
        } catch (Exception $e) {
            echo Response::json(["message" => "Erro: $e"], 500);
        }
    }

    public function updateOrderStatus() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if (!isset($data['order_id']) || !isset($data['status'])) {
            echo Response::json([
                "Dados ausentes ou incorretos"
            ], 400);
            return;
        }
    
        $result = OrderHandler::updateOrderStatus($data['order_id'], $data['status']);
    
        // Erro (status inválido, pedido inexistente ou status já está atribuído)
        if ($result['status'] === 'error') {
            echo Response::json([
                "message" => $result['message']
            ], 400);
            return;
        }
    
        echo Response::json([
            "message" => $result['message']
        ], 200);
    }

}