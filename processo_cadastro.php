<?php
require_once 'config.php';
header('Content-Type: application/json'); // Avisa que a resposta será em JSON

// Recebe os dados enviados pelo JavaScript (fetch)
$dados = json_decode(file_get_contents("php://input"));

if ($dados) {
    $nome = trim($dados->nome);
    $email = trim($dados->email);
    $whatsapp = trim($dados->whatsapp);
    $senha_pura = $dados->senha;

    // 1. Verificação de campos vazios (Segurança extra no backend)
    if (empty($nome) || empty($email) || empty($whatsapp) || empty($senha_pura)) {
        echo json_encode(["status" => "erro", "mensagem" => "Por favor, preencha todos os campos."]);
        exit;
    }

    // 🛡️ 2. TRAVA VIP: Impede que alguém cadastre o e-mail do administrador
    if (strtolower($email) === 'muffim.kat2010@gmail.com') {
        echo json_encode(["status" => "erro", "mensagem" => "Este e-mail é reservado para a administração do sistema."]);
        exit;
    }

    // Criptografa a senha com segurança
    $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

    try {
        // 3. Verifica se o e-mail já existe no banco de dados
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->rowCount() > 0) {
            echo json_encode(["status" => "erro", "mensagem" => "Este e-mail já está cadastrado!"]);
            exit;
        }

        // 4. Insere o novo usuário
        $sql = "INSERT INTO usuarios (nome, email, whatsapp, senha) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $whatsapp, $senha_hash]);

        // Retorna sucesso
        echo json_encode(["status" => "sucesso", "mensagem" => "Cadastro realizado com sucesso!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro no banco: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Nenhum dado recebido."]);
}
?>