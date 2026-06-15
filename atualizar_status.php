<?php
require_once 'config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"));

if ($dados && isset($dados->pedido_id) && isset($dados->novo_status)) {
    try {

        $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dados->novo_status, $dados->pedido_id]);

        echo json_encode(["status" => "sucesso", "mensagem" => "Status atualizado!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao atualizar: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos."]);
}
?>