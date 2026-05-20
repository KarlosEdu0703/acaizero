<?php
require_once 'config.php';
header('Content-Type: application/json');

// Recebe o ID do usuário enviado pelo JavaScript
$dados = json_decode(file_get_contents("php://input"));

if ($dados && isset($dados->usuario_id)) {
    $usuario_id = $dados->usuario_id;

    try {
        // Busca os pedidos daquele usuário específico, ordenando pelos mais recentes
        $stmt = $pdo->prepare("SELECT id, data_pedido, total, status FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC");
        $stmt->execute([$usuario_id]);
        $pedidos = $stmt->fetchAll();

        echo json_encode([
            "status" => "sucesso", 
            "pedidos" => $pedidos
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "erro", 
            "mensagem" => "Erro ao consultar o banco de dados: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "erro", 
        "mensagem" => "Usuário não identificado."
    ]);
}
?>