<?php
session_start();
require_once 'config.php';

// Se não estiver logado, manda pro login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    // Busca dados do usuário
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        session_destroy();
        header("Location: login.php");
        exit;
    }

    // Busca histórico de pedidos (evita erro se a tabela estiver vazia)
    $pedidos = [];
    $stmt_p = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC");
    $stmt_p->execute([$_SESSION['user_id']]);
    $pedidos = $stmt_p->fetchAll();

} catch (PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | Sabor & Arte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6a1b9a; --bg: #f8f9fa; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); }
        .profile-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
        .nav-pills .nav-link.active { background-color: var(--primary) !important; }
        .badge-status { border-radius: 50px; padding: 5px 12px; font-size: 0.7rem; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="color: var(--primary)">
            <i class="fas fa-arrow-left me-2"></i> Voltar ao Cardápio
        </a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card profile-card p-4 text-center">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuario['nome']) ?>&background=6a1b9a&color=fff" class="rounded-circle mx-auto mb-3" width="100">
                <h4 class="fw-bold"><?= htmlspecialchars($usuario['nome']) ?></h4>
                <hr>
                <div class="nav flex-column nav-pills" role="tablist">
                    <button class="nav-link active mb-2 text-start" data-bs-toggle="pill" data-bs-target="#pedidos" type="button">Meus Pedidos</button>
                    <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#dados" type="button">Minha Conta</button>
                    <a href="logout.php" class="nav-link text-danger text-start">Sair</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card profile-card p-4">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pedidos">
                        <h5 class="fw-bold mb-4">Histórico de Pedidos</h5>
                        <table class="table">
                            <thead><tr><th>ID</th><th>Data</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach($pedidos as $p): ?>
                                <tr>
                                    <td>#<?= $p['id'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td>
                                    <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                                    <td><span class="badge bg-secondary"><?= $p['status'] ?></span></td>
                                </tr>
                                <?php endforeach; if(empty($pedidos)) echo "<tr><td colspan='4'>Nenhum pedido.</td></tr>"; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="dados">
                        <h5 class="fw-bold mb-4">Seus Dados</h5>
                        <form action="atualizar_perfil.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Nome</label>
                                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($usuario['whatsapp'] ?? '') ?>">
                                </div>
                                <div class="col-12">
                                    <label>Endereço</label>
                                    <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($usuario['endereco'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Bairro</label>
                                    <input type="text" name="bairro" class="form-control" value="<?= htmlspecialchars($usuario['bairro'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Cidade</label>
                                    <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($usuario['cidade'] ?? '') ?>">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3" style="background: var(--primary); border:none">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>