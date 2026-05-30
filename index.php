<?php
// 1. Importa as configurações de conexão com o banco de dados
require_once 'config.php';

try {
    // 2. Faz a consulta para pegar todos os produtos da tabela
    // ⚠️ ATENÇÃO: Confirme se o nome da sua tabela no banco é exatamente 'produtos'
    $stmt = $pdo->query("SELECT * FROM produtos"); 
    
    // 3. Salva os produtos encontrados dentro da variável que o foreach está procurando
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Caso dê algum erro no banco, criamos a variável como um array vazio para não quebrar a página
    $produtos = [];
    // Opcional: Descomente a linha abaixo se quiser ver o erro do banco na tela enquanto testa
    // echo "Erro no banco: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabor & Arte | Açaí, Burgers e Porções</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>

     :root {
            --primary-color: #511281;
            --primary-light: #7b2cbf;
            --secondary-color: #ffca28;
            --dark-bg: #121212;
            --text-dark: #2b2b2b;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
            scroll-behavior: smooth;
            
            /* CONFIGURAÇÃO DO FUNDO OPACO CLARINHO */
            /* Usando a imagem fundo.avif do seu projeto com uma camada branca de 94% de opacidade por cima */
            background: linear-gradient(rgba(252, 252, 252, 0.94), rgba(252, 252, 252, 0.94)), 
                        url('fundo.avif');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }

        h1, h2, h3, h4, h5, .fw-bold, .btn, .navbar-brand {
            font-family: 'Poppins', sans-serif !important;
        }

        /* AJUSTE DE RESPONSIVIDADE NA NAVBAR */
        .navbar {
            background: rgba(255,255,255,0.85) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 12px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary-color) !important;
            font-size: 1.25rem;
        }
        
        @media (min-width: 576px) {
            .navbar-brand { font-size: 1.5rem; }
        }

        .navbar-brand i {
            color: var(--primary-color);
        }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .cart-badge {
            background-color: var(--secondary-color);
            color: #000;
            font-weight: 800;
            border-radius: 50px;
            padding: 2px 8px;
            font-size: 0.7rem;
            position: absolute;
            top: -5px;
            right: -10px;
            border: 2px solid white;
        }

        .btn-profile {
            background-color: #f4f4f4;
            border-radius: 14px;
            padding: 8px 18px;
            color: #333;
            text-decoration: none;
            font-weight: 600;
            border: none;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        @media (min-width: 576px) {
            .btn-profile {
                padding: 10px 24px;
                font-size: 1rem;
            }
        }

        .btn-profile:hover {
            background-color: var(--primary-color);
            color: white !important;
        }

        /* HERO SECTION RESPONSIVA */
        .hero-section {
            background:
            linear-gradient(to bottom, rgba(18,18,18,0.45), rgba(18,18,18,0.8)),
            url('https://images.unsplash.com/photo-1594179047519-f347310d3322?q=80&w=2070&auto=format&fit=crop');

            background-size: cover;
            background-position: center;
            background-attachment: fixed;

            /* Altura menor em celulares, maior em telas grandes */
            padding: 100px 0 80px 0;
        }
        
        @media (min-width: 768px) {
            .hero-section { padding: 160px 0 130px 0; }
        }
        
        /* Ajuste de fontes do Hero para telas menores */
        .hero-section h1 {
            font-size: 2rem;
        }
        @media (min-width: 768px) {
            .hero-section h1 { font-size: 3.5rem; }
        }
        
        .hero-section p {
            font-size: 1.1rem !important;
        }
        @media (min-width: 768px) {
            .hero-section p { font-size: 1.25rem !important; }
        }

        /* BOTÕES DE FILTRO MAIS AMIGÁVEIS AO TOQUE */
        .btn-group .filter-btn {
            border: 1px solid #e2e8f0;
            border-radius: 30px !important;
            margin: 4px;
            padding: 8px 18px;
            color: #555;
            background: white;
            font-size: 0.9rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: var(--transition);
        }

        .filter-btn.active {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 4px 10px rgba(81, 18, 129, 0.25);
        }

        /* CARD DOS PRODUTOS MELHORADO */
        .product-card {
            border-radius: 24px;
            overflow: hidden;
            border: none;
            background: #fff;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }

        .img-container {
            overflow: hidden;
            position: relative;
        }

        /* Imagem do produto menor em celulares para não quebrar o layout */
        .card-img-top {
            height: 180px;
            object-fit: cover;
            transition: var(--transition);
        }
        
        @media (min-width: 576px) {
            .card-img-top { height: 220px; }
        }

        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        .btn-add-cart {
            border-radius: 14px;
            padding: 10px;
            font-weight: 700;
            background-color: var(--primary-color);
            border: none;
            font-size: 0.95rem;
        }

        .btn-add-cart:hover {
            background-color: var(--primary-light);
        }
        
        /* Ajuste das margens laterais da grade em telas pequenas */
        @media (max-width: 576px) {
            #products-grid {
                padding: 0 5px;
            }
        }

    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">

    <div class="container">

        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-ice-cream"></i>
            AÇAIZERO
        </a>

        <div class="d-flex align-items-center order-lg-3">

            <a class="nav-link px-3 position-relative me-3 text-dark"
               href="#"
               data-bs-toggle="modal"
               data-bs-target="#cartModal">

                <i class="fas fa-shopping-basket fa-lg"></i>

                <span class="cart-badge" id="cart-count">0</span>

            </a>

            <div id="user-area">

                <a href="login.html" class="btn-profile text-decoration-none">

                    <i class="fas fa-user-circle me-2"></i>
                    Entrar

                </a>

            </div>

        </div>

        <button class="navbar-toggler border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link px-3" href="#cardapio">
                        Cardápio
                    </a>
                </li>

                <li class="nav-item" id="menu-cadastro">

                    <a class="nav-link px-3" href="cadastro.html">
                        Cadastrar
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<header class="hero-section text-white text-center">

    <div class="container" data-aos="zoom-out">

        <h1 class="display-3 fw-bold mb-3">
            O Melhor Sabor da Região
        </h1>

        <p class="lead mb-4 fs-4 text-light">
            Hambúrgueres artesanais, porções e açaís feitos com amor.
        </p>

        <a href="#cardapio"
           class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow border-0 rounded-pill">

            QUERO PEDIR AGORA
        </a>

    </div>

</header>

<main class="container my-5" id="cardapio">

    <div class="text-center mb-5" data-aos="fade-up">

        <h2 class="fw-bold display-6">Nosso Cardápio</h2>

        <div class="btn-group mt-3 flex-wrap d-inline-flex">

    <button class="btn filter-btn active" data-filter="all">
        ✨ Tudo
    </button>

    <button class="btn filter-btn" data-filter="burger">
        🍔 Burgers
    </button>

    <button class="btn filter-btn" data-filter="acai">
        🍧 Açaí
    </button>

    <button class="btn filter-btn" data-filter="combo">
        🚀 Combos
    </button>

    <button class="btn filter-btn" data-filter="porcao">
        🍟 Porções
    </button>
    
    <button class="btn filter-btn" data-filter="bebida">
        🥤 Bebidas
    </button>

</div>

    </div>

    <div class="row g-4" id="products-grid">

        <?php foreach($produtos as $produto): ?>

            <?php

            $badgeClass = 'bg-warning text-dark';

            switch(strtolower($produto['tag'])) {

            case 'trincando':
    $badgeClass = 'bg-info text-white';
    break;
    
                case 'novo':
                    $badgeClass = 'bg-danger text-white';
                    break;

                case 'promoção':
                case 'promocao':
                    $badgeClass = 'bg-danger text-white';
                    break;

                case 'energético':
                case 'energetico':
                    $badgeClass = 'bg-info text-white';
                    break;

                case 'refrescante':
                    $badgeClass = 'bg-info text-white';
                    break;

                case 'crocante':
                    $badgeClass = 'bg-success text-white';
                    break;

                case 'para dividir':
                    $badgeClass = 'bg-dark text-white';
                    break;
            }

            ?>

            <div class="col-12 col-md-6 col-lg-4 product-item"
                 data-category="<?= strtolower($produto['categoria']) ?>"
                 data-aos="fade-up">

                <div class="card h-100 shadow-sm product-card">

                    <div class="img-container">

                        <img
                            src="<?= htmlspecialchars($produto['imagem']) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($produto['nome']) ?>"
                        >

                    </div>

                    <div class="card-body d-flex flex-column p-4">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($produto['tag']) ?>
                            </span>

                            <span class="h5 mb-0 fw-bold text-primary">
                                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                            </span>

                        </div>

                        <h5 class="card-title fw-bold">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            <?= htmlspecialchars($produto['descricao']) ?>
                        </p>

                        <button
                            class="btn btn-primary w-100 fw-bold btn-add-cart"
                            data-id="<?= $produto['id'] ?>"
                            data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                            data-preco="<?= $produto['preco'] ?>"
                        >
                            Adicionar
                        </button>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>

<div class="modal fade" id="cartModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg"
             style="border-radius:24px; overflow:hidden;">

            <div class="modal-header border-0 bg-primary text-white p-4">

                <h5 class="modal-title fw-bold">
                    <i class="fas fa-shopping-basket me-2"></i>
                    Seu Pedido
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body p-4">

                <div id="cart-items" class="mb-3"></div>

                <div class="d-flex justify-content-between border-top pt-3">

                    <span class="h4 fw-bold mb-0">Total:</span>

                    <span class="h3 fw-bold text-success mb-0" id="cart-total">
                        R$ 0,00
                    </span>

                </div>


            </div>
            <div class="modal-footer border-0 p-4 pt-0">

    <button
        type="button"
        class="btn btn-primary w-100 fw-bold py-3"
        id="checkout-btn"
        style="border-radius:14px; font-size:1rem;">

        Finalizar Compra

    </button>

</div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>

AOS.init({
    duration: 800,
    once: true
});

let cart = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartUI() {

    const cartCount = document.getElementById('cart-count');
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    cartCount.innerText = cart.reduce((acc, item) => acc + item.quantidade, 0);

    cartItems.innerHTML = '';

    let total = 0;

    if(cart.length === 0) {

        cartItems.innerHTML = `
            <p class="text-center text-muted">
                Seu carrinho está vazio.
            </p>
        `;

    } else {

        cart.forEach(item => {

            total += item.preco * item.quantidade;

            cartItems.innerHTML += `
                <div class="d-flex justify-content-between mb-3">

                    <div>
                        <h6 class="fw-bold mb-0">${item.nome}</h6>

                        <small class="text-muted">
                            ${item.quantidade}x
                        </small>
                    </div>

                    <div class="fw-bold">
                        R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}
                    </div>

                </div>
            `;
        });
    }

    cartTotal.innerText =
        `R$ ${total.toFixed(2).replace('.', ',')}`;

    localStorage.setItem('cart', JSON.stringify(cart));
}

function addToCart(product) {

    const item = cart.find(i => i.id === product.id);

    if(item) {
        item.quantidade++;
    } else {
        cart.push(product);
    }

    updateCartUI();

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Adicionado ao carrinho!',
        showConfirmButton: false,
        timer: 1200
    });
}

document.addEventListener('click', e => {

    const btn = e.target.closest('.btn-add-cart');

    if(btn) {

        addToCart({
            id: btn.dataset.id,
            nome: btn.dataset.nome,
            preco: parseFloat(btn.dataset.preco),
            quantidade: 1
        });
    }
});

document.querySelectorAll('.filter-btn').forEach(btn => {

    btn.addEventListener('click', () => {

        document
            .querySelector('.filter-btn.active')
            .classList.remove('active');

        btn.classList.add('active');

        const filter = btn.dataset.filter;

        document.querySelectorAll('.product-item').forEach(item => {

            item.style.display =
                (filter === 'all' ||
                 item.dataset.category === filter)
                ? 'block'
                : 'none';
        });
    });
});

updateCartUI();
// CONTROLE DE SESSÃO DO USUÁRIO

function checarUsuario() {

    const userArea = document.getElementById('user-area');
    const menuCadastro = document.getElementById('menu-cadastro');

    let usuarioLogado = null;

    try {

        const cacheData = localStorage.getItem('usuario_logado');

        if(cacheData) {
            usuarioLogado = JSON.parse(cacheData);
        }

    } catch(e) {

        localStorage.removeItem('usuario_logado');
    }

    if(usuarioLogado && usuarioLogado.nome) {

        if(menuCadastro) {
            menuCadastro.style.display = 'none';
        }

        const primeiroNome =
            usuarioLogado.nome.split(' ')[0];

        const urlAvatar =
            usuarioLogado.foto ||
            `https://ui-avatars.com/api/?name=${encodeURIComponent(usuarioLogado.nome)}&background=511281&color=fff`;

        userArea.innerHTML = `

            <div class="dropdown">

                <button
                    class="btn-profile dropdown-toggle d-flex align-items-center"
                    type="button"
                    data-bs-toggle="dropdown"
                    style="border:none;">

                    <img
                        src="${urlAvatar}"
                        class="rounded-circle me-2"
                        width="24"
                        height="24"
                        style="object-fit:cover;">

                    Olá, ${primeiroNome}

                </button>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2"
                    style="border-radius:14px;">

                    <li>

                        <a class="dropdown-item p-2 px-3"
                          href="perfil.html">

                           <i class="fas fa-user-circle me-2 text-muted"></i>
                           Meu Perfil

                        </a>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <a class="dropdown-item text-danger p-2 px-3"
                           href="#"
                           onclick="logout(event)">

                           <i class="fas fa-sign-out-alt me-2"></i>
                           Sair

                        </a>

                    </li>

                </ul>

            </div>
        `;

    } else {

        if(menuCadastro) {
            menuCadastro.style.display = 'block';
        }

        userArea.innerHTML = `

            <a href="login.html"
               class="btn-profile text-decoration-none">

                <i class="fas fa-user-circle me-2"></i>
                Entrar

            </a>
        `;
    }
}

function logout(event) {

    event.preventDefault();

    localStorage.removeItem('usuario_logado');

    window.location.reload();
}

checarUsuario();

// FINALIZAR COMPRA VIA WHATSAPP

// FINALIZAR COMPRA SALVANDO NO BANCO E INDO PARA O WHATSAPP
document.getElementById('checkout-btn').addEventListener('click', () => {

    if(cart.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrinho vazio',
            text: 'Adicione itens antes de finalizar.'
        });
        return;
    }

    // 1. Recupera o usuário logado para obter o ID
    const cacheData = localStorage.getItem('usuario_logado');
    if (!cacheData) {
        Swal.fire({
            icon: 'error',
            title: 'Acesso necessário',
            text: 'Você precisa estar logado para finalizar o pedido!',
            footer: '<a href="login.html" class="btn btn-sm btn-primary">Fazer Login agora</a>'
        });
        return;
    }

    const usuarioLogado = JSON.parse(cacheData);

    // ⚠️ ATENÇÃO: Verifique se no seu processo_login.php você salvou como 'id' ou 'usuario_id'
    if (!usuarioLogado.id) {
        Swal.fire({
            icon: 'error',
            title: 'Erro de Identificação',
            text: 'Não foi possível encontrar o ID do usuário logado. Refaça o login.'
        });
        return;
    }

    // 2. Calcula o total do pedido
    let total = 0;
    cart.forEach(item => {
        total += item.preco * item.quantidade;
    });

    // 3. Monta o objeto exatamente como o processo_pedido.php espera
    const dadosPedido = {
        usuario_id: usuarioLogado.id, // ID do cliente vindo do localStorage
        total: total,
        itens: cart // Array contendo {nome, preco, quantidade}
    };

    // Exibe um alerta de carregamento enquanto salva no banco
    Swal.fire({
        title: 'Processando seu pedido...',
        text: 'Por favor, aguarde.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // 4. Envia os dados para o servidor via API Fetch
    fetch('processo_pedido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosPedido)
    })
    .then(response => response.json())
    .then(data => {
       if (data.status === 'sucesso') {
            
            // 5. Se salvou no banco com sucesso, gera a mensagem linda do WhatsApp
            let msgCompleta = `🍇 *SABOR & ARTE - NOVO PEDIDO* 🍔\n`;
            msgCompleta += `===============================\n\n`;
            msgCompleta += `👤 *Cliente:* ${usuarioLogado.nome}\n`;
            msgCompleta += `📱 *WhatsApp:* ${usuarioLogado.whatsapp || 'Não informado'}\n\n`;
            
            msgCompleta += `📍 *ENDEREÇO DE ENTREGA:*\n`;
            if (usuarioLogado.endereco) {
                msgCompleta += `• *Rua:* ${usuarioLogado.endereco}\n`;
                msgCompleta += `• *Bairro:* ${usuarioLogado.bairro || 'Não informado'}\n`;
                msgCompleta += `• *Cidade:* ${usuarioLogado.cidade || 'Não informada'}\n\n`;
            } else {
                msgCompleta += `⚠️ _Endereço não cadastrado (Combinar no chat)_\n\n`;
            }

            msgCompleta += `🛒 *ITENS DO PEDIDO:*\n`;
            cart.forEach(item => {
                const subtotalItem = (item.preco * item.quantidade).toFixed(2).replace('.', ',');
                msgCompleta += `• *${item.quantidade}x* ${item.nome} _(R$ ${subtotalItem})_\n`;
            });
            
            msgCompleta += `\n===============================\n`;
            msgCompleta += `💰 *TOTAL DO PEDIDO:* R$ ${total.toFixed(2).replace('.', ',')}\n`;
            msgCompleta += `===============================\n\n`;
            msgCompleta += `✨ _Pedido gerado automaticamente pelo site!_`;

            // Número de telefone do estabelecimento
            const telefone = '5531993013900';

            Swal.fire({
                icon: 'success',
                title: 'Pedido Confirmado!',
                text: 'Seu pedido foi registrado no sistema. Redirecionando para o WhatsApp...',
                timer: 2500,
                showConfirmButton: false
            }).then(() => {
                // Limpa o carrinho local
                cart = [];
                updateCartUI();

                // Abre o WhatsApp convertendo a mensagem perfeitamente para link
                window.open(`https://wa.me/${telefone}?text=${encodeURIComponent(msgCompleta)}`, '_blank');
            });

        } else {
            // Caso o processo_pedido.php retorne algum erro de validação/banco
            Swal.fire({
                icon: 'error',
                title: 'Erro ao salvar pedido',
                text: data.mensagem
            });
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro de Conexão',
            text: 'Não foi possível conectar ao servidor para salvar seu pedido.'
        });
    });
});

</script>

</body>
</html>