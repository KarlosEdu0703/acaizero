<?php
require_once 'config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"));

if ($dados) {
    $nome = trim($dados->nome);
    $email = trim($dados->email);
    $endereco = trim($dados->endereco);

    if (empty($nome) || empty($email) || empty($endereco)) {
        echo json_encode(["status" => "erro", "mensagem" => "Preencha nome, email e endereço."]);
        exit;
    }

    // Gera uma senha aleatória segura que o usuário nem precisa saber (checkout como visitante)
    $senha_aleatoria = bin2hex(random_bytes(6)); 
    $senha_hash = password_hash($senha_aleatoria, PASSWORD_DEFAULT);
    $whatsapp = 'Não informado';

    try {
        // Verifica se o email já existe
        $stmt_check = $pdo->prepare("SELECT id, nome, email, endereco FROM usuarios WHERE email = ?");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->rowCount() > 0) {
            // Se já existe, apenas atualiza o endereço (facilita para quem já comprou antes)
            $usuarioDB = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            $stmt_update = $pdo->prepare("UPDATE usuarios SET endereco = ? WHERE email = ?");
            $stmt_update->execute([$endereco, $email]);

            echo json_encode([
                "status" => "sucesso", 
                "usuario" => [
                    "id" => $usuarioDB['id'], // CORREÇÃO: Garante o ID correto mapeado no admin
                    "usuario_id" => $usuarioDB['id'],
                    "nome" => $usuarioDB['nome'],
                    "email" => $email,
                    "endereco" => $endereco,
                    "tipo" => "cliente"
                ]
            ]);
            exit;
        }

        // Se for novo, insere no banco.
        $sql = "INSERT INTO usuarios (nome, email, whatsapp, senha, endereco) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $whatsapp, $senha_hash, $endereco]);
        
        // Pega o ID gerado para devolver ao front-end
        $id_gerado = $pdo->lastInsertId();

        echo json_encode([
            "status" => "sucesso", 
            "usuario" => [
                "id" => $id_gerado,
                "usuario_id" => $id_gerado, // Duplicado por segurança (para evitar quebras no login tradicional)
                "nome" => $nome,
                "email" => $email,
                "endereco" => $endereco,
                "tipo" => "cliente"
            ]
        ]);
    } catch (PDOException $e) {
        // Fallback: se der erro por falta da coluna endereço, tenta inserir sem ela
        if (strpos($e->getMessage(), "Unknown column 'endereco'") !== false) {
             $sql = "INSERT INTO usuarios (nome, email, whatsapp, senha) VALUES (?, ?, ?, ?)";
             $stmt = $pdo->prepare($sql);
             $stmt->execute([$nome, $email, $whatsapp, $senha_hash]);
             $id_gerado = $pdo->lastInsertId();
             echo json_encode([
                "status" => "sucesso", 
                "usuario" => [
                    "id" => $id_gerado,
                    "usuario_id" => $id_gerado,
                    "nome" => $nome,
                    "email" => $email,
                    "endereco" => $endereco,
                    "tipo" => "cliente"
                ]
            ]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Erro no banco: " . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Nenhum dado recebido."]);
}
?>