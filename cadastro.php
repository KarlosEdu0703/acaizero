<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Sabor & Arte</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --primary-color: #6a1b9a; 
            --secondary-color: #ffca28;
            --bg-light: #f8f9fa; 
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        .auth-header {
            background-color: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .auth-body {
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #444;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 27, 154, 0.1);
        }

        .btn-register {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 800;
            transition: all 0.3s;
            color: white;
        }

        .btn-register:hover {
            background-color: #4a148c;
            transform: translateY(-2px);
            color: white;
        }

        .auth-footer {
            text-align: center;
            padding-bottom: 30px;
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <h3 class="fw-bold mb-0">Sabor & Arte</h3>
        <p class="small opacity-75 mb-0">Crie sua conta e comece a pedir!</p>
    </div>

    <div class="auth-body">
        <form id="registerForm" action="processa_cadastro.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="nome" class="form-control border-start-0" placeholder="Ex: João Silva" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fab fa-whatsapp text-muted"></i></span>
                    <input type="tel" name="whatsapp" class="form-control border-start-0" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" id="password" name="senha" class="form-control" placeholder="******" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label">Confirmar</label>
                    <input type="password" id="confirm_password" class="form-control" placeholder="******" required>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100 mb-3">
                CRIAR MINHA CONTA
            </button>
        </form>
    </div>

    <div class="auth-footer">
        <p class="text-muted small">Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const pass = document.getElementById('password').value;
        const confirmPass = document.getElementById('confirm_password').value;

        if (pass !== confirmPass) {
            e.preventDefault(); // Impede o envio do formulário
            Swal.fire({
                icon: 'error',
                title: 'Ops!',
                text: 'As senhas não coincidem. Tente novamente.',
                confirmButtonColor: '#6a1b9a'
            });
        }
    });
</script>

</body>
</html>