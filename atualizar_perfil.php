<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $sql = "UPDATE usuarios SET nome=?, whatsapp=?, endereco=?, bairro=?, cidade=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nome'], 
        $_POST['whatsapp'], 
        $_POST['endereco'], 
        $_POST['bairro'], 
        $_POST['cidade'], 
        $_SESSION['user_id']
    ]);
    header("Location: perfil.php?sucesso=1");
}