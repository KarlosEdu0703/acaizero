<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografia forte

    try {
        $sql = "INSERT INTO usuarios (nome, email, whatsapp, senha) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $whatsapp, $senha]);

        echo "<script>alert('Cadastro realizado!'); window.location.href='login.php';</script> text";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}