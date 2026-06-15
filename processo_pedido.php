<?php
require_once 'config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"));

if ($dados && isset($dados->usuario_id) && !empty($dados->itens)) {
    try {

        $pdo->beginTransaction(); 

        $sqlPedido = "INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'Pendente')";
        $stmtPedido = $pdo->prepare($sqlPedido);
        $stmtPedido->execute([$dados->usuario_id, $dados->total]);
        
        $pedidoId = $pdo->lastInsertId(); 

        $sqlItem = "INSERT INTO itens_pedido (pedido_id, produto_nome, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmtItem = $pdo->prepare($sqlItem);

        foreach ($dados->itens as $item) {
            $stmtItem->execute([$pedidoId, $item->nome, $item->quantidade, $item->preco]);
        }

        echo json_encode(["status" => "sucesso", "mensagem" => "Pedido registrado com sucesso!"]);
    } catch (PDOException $e) {
        $pdo->rollBack(); // Desfaz tudo se der erro
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar pedido: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos ou cliente não identificado."]);
}
?>