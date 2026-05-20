<?php
require_once 'config.php';
header('Content-Type: application/json');

try {
    // Busca todos os pedidos e faz um JOIN com a tabela usuários para pegar o nome e whatsapp do cliente
    $sql = "SELECT p.id, u.nome AS cliente, u.whatsapp, p.total, p.status, p.data_pedido 
            FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.data_pedido DESC";
            
    $stmt = $pdo->query($sql);
    $pedidos = $stmt->fetchAll();

    echo json_encode(["status" => "sucesso", "pedidos" => $pedidos]);
} catch (PDOException $e) {
    echo json_encode(["status" => "erro", "mensagem" => "Erro no banco: " . $e->getMessage()]);
}
?>