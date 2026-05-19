<?php
require_once 'config.php';
header('Content-Type: application/json'); // Avisa que a resposta será em JSON

// Recebe os dados enviados pelo JavaScript (fetch)
$dados = json_decode(file_get_contents("php://input"));

if ($dados) {
    $nome = $dados->nome;
    $email = $dados->email;
    $whatsapp = $dados->whatsapp;
    $senha = password_hash($dados->senha, PASSWORD_DEFAULT);

    try {
        // Verifica se o e-mail já existe no banco de dados
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->rowCount() > 0) {
            echo json_encode(["status" => "erro", "mensagem" => "Este e-mail já está cadastrado!"]);
            exit;
        }

        // Insere o novo usuário
        $sql = "INSERT INTO usuarios (nome, email, whatsapp, senha) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $whatsapp, $senha]);

        // Retorna sucesso
        echo json_encode(["status" => "sucesso", "mensagem" => "Cadastro realizado!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro no banco: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Nenhum dado recebido."]);
}
?>