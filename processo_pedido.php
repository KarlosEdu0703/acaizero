<?php
require_once 'config.php';
header('Content-Type: application/json');

// Recebe os dados do carrinho enviados pelo JavaScript
$dados = json_decode(file_get_contents("php://input"));

if ($dados && isset($dados->usuario_id) && !empty($dados->itens)) {
    try {
        // Iniciamos uma transação (garante que, se der erro nos itens, o pedido não fica pela metade)
        $pdo->beginTransaction(); 

        // 1. Salva o pedido principal na tabela 'pedidos'
        $sqlPedido = "INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'Pendente')";
        $stmtPedido = $pdo->prepare($sqlPedido);
        $stmtPedido->execute([$dados->usuario_id, $dados->total]);
        
        // Pega o ID do pedido que acabou de ser gerado
        $pedidoId = $pdo->lastInsertId(); 

        // 2. Salva cada item na tabela 'itens_pedido'
        $sqlItem = "INSERT INTO itens_pedido (pedido_id, produto_nome, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmtItem = $pdo->prepare($sqlItem);

        foreach ($dados->itens as $item) {
            $stmtItem->execute([$pedidoId, $item->nome, $item->quantidade, $item->preco]);
        }

        // Confirma a transação no banco de dados
        $pdo->commit();

        echo json_encode(["status" => "sucesso", "mensagem" => "Pedido registrado com sucesso!"]);
    } catch (PDOException $e) {
        $pdo->rollBack(); // Desfaz tudo se der erro
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar pedido: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos ou cliente não identificado."]);
}
?>