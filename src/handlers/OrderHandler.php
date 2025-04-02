<?php

namespace src\handlers;

use Exception;
use src\models\Orders;
use src\handlers\UserHandler;
use src\models\Items;

class OrderHandler
{  

    public static function createOrder($data)
    {
        try {
            $user_name = $data['name'] ?? null;
            $phone = $data['phone'] ?? null;
            $street = $data['street'] ?? null;
            $number = $data['number'] ?? null;
            $neighborhood = $data['neighborhood'] ?? null;
            $complement = $data['complement'] ?? null;

            $observation = $data['order_observation'] ?? '';
            $payment_method = $data['payment_method'] ?? '';
            $items = $data['items'] ?? [];

            if (!$user_name || !$phone || !$street || !$number || !$neighborhood) {
                return ['status' => 'error', 'message' => 'Dados de usuário incompletos'];
            }

            if (!is_array($items) || empty($items)) {
                return ['status' => 'error', 'message' => 'Nenhum item no pedido'];
            }

            $UserFound = UserHandler::findUserByPhone($phone);

            if (!$UserFound) {
                $user = UserHandler::createCommonUser(
                    $user_name,
                    $phone,
                    $street,
                    $number,
                    $neighborhood,
                    $complement
                );

                
            }

            $user = UserHandler::findUserByPhone($phone);
            if (!$user) {
                return ['status' => 'error', 'message' => 'Erro ao criar usuário'];
            }
           

            Orders::insert([
                'user_id' => $user['id'],
                'status' => 'Pendente',
                'payment_method' => $payment_method,
                'observation' => $observation,
                'subtotal' => self::calculateSubTotal($items),                
                'created_at' => date('Y-m-d H:i:s')
            ])
            ->execute();

            $order = Orders::select()
                ->where('user_id', $user['id'])
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();

            if (!$order['id']) {
                return ['status' => 'error', 'message' => 'Erro ao criar pedido'];
            }

            // ✅ Adicionando os itens ao pedido
            foreach ($items as $item) {
                Items::insert([
                    'order_id' => (int)$order['id'],
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'observation' => $item['observation'] ?? '',
                    'created_at' => date('Y-m-d H:i:s')
                ])
                    ->execute();
            }

            return [
                'order' => $order
            ];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public static function calculateSubTotal($items)
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $product = ProductHandler::getProduct($item['product_id']);
            if ($product) {
                $subtotal += $product[0]['price'] * $item['amount'];
            }
        }

        return $subtotal;
    }

    public static Function listOrder() {
        return Orders::select([
            "orders.id",
            "orders.status as order_status",
            "orders.observation as order_observation",
            "orders.payment_method",
            "orders.user_id",
            "orders.created_at",
            "orders.updated_at",
            "orders.subtotal",
            "products.id as product_id",
            "products.name as product_name",
            "products.price as product_price",
            "items.id as item_id",
            "items.amount",
            "items.observation as item_observation",
            "users.name as user_name",
            "users.phone",
            "users.street",
            "users.number",
            "users.neighborhood",
            "users.complement",
        ])
        ->join("users", "users.id", "=", "orders.user_id")
        ->join("items", "items.order_id", "=", "orders.id")
        ->join("products", "products.id", "=", "items.product_id")
        ->get();
                
         
    }

}
