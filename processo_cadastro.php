<?php
header('Content-Type: application/json');
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $whatsapp = trim($_POST['whatsapp'] ?? '');

    if (empty($nome) || empty($email) || empty($senha) || empty($whatsapp)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.']);
        exit;
    }

    try {
        // Verifica e-mail duplicado direto no banco real
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->rowCount() > 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado!']);
            exit;
        }

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere no banco novo
        $sql = "INSERT INTO usuarios (nome, email, senha, whatsapp) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $sucesso = $stmt->execute([$nome, $email, $senha_hash, $whatsapp]);

        if ($sucesso) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_nome'] = $nome;

            echo json_encode(['sucesso' => true, 'mensagem' => "Seja bem-vindo à nossa loja!"]);
            exit;
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Não foi possível salvar os dados no momento.']);
            exit;
        }

    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
        exit;
    }
}
?>