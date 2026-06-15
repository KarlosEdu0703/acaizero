<?php
require_once 'config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"));

if ($dados && isset($dados->id)) {
    $id = $dados->id;
    $nome = trim($dados->nome);
    $whatsapp = trim($dados->whatsapp);
    $endereco = trim($dados->endereco);
    $bairro = trim($dados->bairro);
    $cidade = trim($dados->cidade);
    $foto = $dados->foto ?? null;

    try {

        $sql = "UPDATE usuarios SET nome = ?, whatsapp = ?, endereco = ?, bairro = ?, cidade = ?, foto = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $whatsapp, $endereco, $bairro, $cidade, $foto, $id]);

        echo json_encode([
            "status" => "sucesso", 
            "mensagem" => "Perfil atualizado com sucesso!"
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "erro", 
            "mensagem" => "Erro ao atualizar na base de dados: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "erro", 
        "mensagem" => "Dados inválidos ou utilizador não identificado."
    ]);
}
?>