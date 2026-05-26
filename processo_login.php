<?php
require_once 'config.php';
header('Content-Type: application/json');

// Recebe os dados enviados pelo JavaScript (fetch)
$dados = json_decode(file_get_contents("php://input"));

if ($dados) {
    $email = trim($dados->email);
    $senha = $dados->senha;

    if ($email === 'muffim.kat2010@gmail.com' && $senha === 'edu123') {
        echo json_encode([
            "status" => "sucesso",
            "tipo" => "admin", // Indica ao frontend para ir para a tela de admin
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
        // Busca o usuário pelo e-mail (Para clientes comuns)
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        // Verifica se o usuário existe e se a senha criptografada está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Removemos a senha do objeto por segurança antes de mandar para o frontend
            unset($usuario['senha']);
            
            // Força o tipo como cliente comum para o frontend diferenciar do admin
            $usuario['tipo'] = 'cliente';
            
            // Retorna sucesso e os dados do usuário para o frontend salvar na sessão
            echo json_encode([
                "status" => "sucesso",
                "tipo" => "cliente", // Indica ao frontend para ir para a página comum
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