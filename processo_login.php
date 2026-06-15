<?php
require_once 'config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"));

if ($dados) {
    $email = trim($dados->email);
    $senha = $dados->senha;

    if ($email === 'muffim.kat2010@gmail.com' && $senha === 'edu123') {
        echo json_encode([
            "status" => "sucesso",
            "tipo" => "admin", 
            "mensagem" => "Acesso autorizado ao Painel de Gestão!",
            "usuario" => [
                "id" => 0,
                "nome" => "Admin Sabor & Arte",
                "email" => $email,
                "tipo" => "admin"
            ]
        ]);
        exit;
    }

    try {
    
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            unset($usuario['senha']);
            
            $usuario['tipo'] = 'cliente';
            
            echo json_encode([
                "status" => "sucesso",
                "tipo" => "cliente",
                "mensagem" => "Login efetuado com sucesso!",
                "usuario" => $usuario
            ]);
        } else {
            echo json_encode([
                "status" => "erro",
                "mensagem" => "E-mail ou senha incorretos."
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Erro no banco de dados: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Nenhum dado recebido."
    ]);
}
?>