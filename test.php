<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=delivery', 'root', 'p3dr0');
    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
