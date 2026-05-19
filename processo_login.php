<?php
require_once 'config.php';
header('Content-Type: application/json');

// Recebe os dados enviados pelo JavaScript (fetch)
$dados = json_decode(file_get_contents("php://input"));

if ($dados) {
    $email = trim($dados->email);
    $senha = $dados->senha;

    try {
        // Busca o usuário pelo e-mail
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        // Verifica se o usuário existe e se a senha criptografada está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Removemos a senha do objeto por segurança antes de mandar para o frontend
            unset($usuario['senha']);
            
            // Retorna sucesso e os dados do usuário para o frontend salvar na sessão
            echo json_encode([
                "status" => "sucesso",
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