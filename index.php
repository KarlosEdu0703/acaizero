<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabor & Arte | Açaí e Burgers</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6a1b9a;
            --secondary-color: #ffca28;
            --dark-bg: #1a1a1a;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; scroll-behavior: smooth; }

        /* Navbar Integrada */
        .navbar { background-color: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(10px); box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        .navbar-brand { font-weight: 800; color: var(--primary-color) !important; letter-spacing: -1px; }
        
        .cart-badge { background-color: var(--secondary-color); color: #000; font-weight: 800; border-radius: 50px; padding: 2px 8px; font-size: 0.7rem; position: absolute; top: -5px; right: -10px; border: 2px solid white; }

        .btn-profile { background-color: #f0f0f0; border-radius: 50px; padding: 8px 20px; color: #333; text-decoration: none; font-weight: 600; transition: var(--transition); border: none; }
        .btn-profile:hover { background-color: var(--primary-color); color: white; }

        /* Hero Section */
        .hero-section { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1594179047519-f347310d3322?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center; padding: 100px 0; }

        /* Cards */
        .product-card { transition: var(--transition); border-radius: 15px; overflow: hidden; border: none; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        .card-img-top { height: 220px; object-fit: cover; }
        
        .filter-btn.active { background-color: var(--primary-color) !important; color: white !important; border-color: var(--primary-color) !important; }
        .quantity-control { display: flex; align-items: center; gap: 10px; background: #f8f9fa; border-radius: 8px; padding: 4px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-ice-cream me-2"></i>SABOR & ARTE</a>
        
        <div class="d-flex align-items-center order-lg-3">
            <a class="nav-link px-3 position-relative me-3" href="#" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-basket fa-lg"></i>
                <span class="cart-badge" id="cart-count">0</span>
            </a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <button class="btn-profile dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>Olá, <?= explode(' ', $_SESSION['user_nome'])[0] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <button class="btn-profile" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-user-circle me-2"></i>Entrar
                </button>
            <?php endif; ?>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link px-3" href="#cardapio">Cardápio</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="cadastro.php">Cadastrar</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section text-white text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">O Melhor Sabor da Região</h1>
        <p class="lead mb-4 fs-4">Hambúrgueres artesanais e açaís feitos com amor.</p>
        <a href="#cardapio" class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow">QUERO PEDIR AGORA</a>
    </div>
</header>

<main class="container my-5" id="cardapio">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">Nosso Cardápio</h2>
        <div class="btn-group mt-3" role="group">
            <button type="button" class="btn btn-outline-dark filter-btn active" data-filter="all">Tudo</button>
            <button type="button" class="btn btn-outline-dark filter-btn" data-filter="burger">Burgers</button>
            <button type="button" class="btn btn-outline-dark filter-btn" data-filter="acai">Açaí</button>
        </div>
    </div>

    <div class="row g-4" id="products-grid">
        <div class="col-12 col-md-6 col-lg-4 product-item" data-category="burger">
            <div class="card h-100 shadow-sm product-card">
                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500" class="card-img-top" alt="Burger">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-warning text-dark">Artesanal</span>
                        <span class="h5 mb-0 fw-bold text-primary">R$ 32,90</span>
                    </div>
                    <h5 class="card-title fw-bold">Monster Burger</h5>
                    <p class="card-text text-muted small flex-grow-1">Pão brioche, 2 blends de 160g, queijo cheddar e bacon crocante.</p>
                    <button class="btn btn-primary w-100 fw-bold btn-add-cart" data-id="1" data-nome="Monster Burger" data-preco="32.90">Adicionar</button>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 product-item" data-category="acai">
            <div class="card h-100 shadow-sm product-card">
                <img src="https://images.unsplash.com/photo-1590301157890-4810ed352733?q=80&w=500" class="card-img-top" alt="Açaí">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-info text-white">Energético</span>
                        <span class="h5 mb-0 fw-bold text-primary">R$ 25,00</span>
                    </div>
                    <h5 class="card-title fw-bold">Açaí Premium 500ml</h5>
                    <p class="card-text text-muted small flex-grow-1">Açaí puro acompanhado de granola premium e morangos frescos.</p>
                    <button class="btn btn-primary w-100 fw-bold btn-add-cart" data-id="2" data-nome="Açaí Premium" data-preco="25.00">Adicionar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4 text-center">
                <h4 class="fw-bold mb-3">Entrar</h4>
                <form action="logar_acao.php" method="POST">
                    <input type="email" name="email" class="form-control mb-3" placeholder="Seu e-mail" required>
                    <input type="password" name="senha" class="form-control mb-3" placeholder="Sua senha" required>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="background: var(--primary-color); border:none;">ENTRAR</button>
                </form>
                <p class="mt-3 small">Não tem conta? <a href="cadastro.php" class="text-primary fw-bold text-decoration-none">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Seu Pedido</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="cart-items" class="mb-3"></div>
                <div class="d-flex justify-content-between border-top pt-3">
                    <span class="h4 fw-bold">Total:</span>
                    <span class="h4 fw-bold text-success" id="cart-total">R$ 0,00</span>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary w-100 fw-bold py-3" id="checkout-btn">Finalizar via WhatsApp</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCartUI() {
        const cartCount = document.getElementById('cart-count');
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');

        cartCount.innerText = cart.reduce((acc, item) => acc + item.quantidade, 0);
        cartItemsContainer.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-center text-muted">Carrinho vazio</p>';
        } else {
            cart.forEach(item => {
                total += item.preco * item.quantidade;
                cartItemsContainer.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><h6 class="mb-0 fw-bold">${item.nome}</h6><small>R$ ${item.preco.toFixed(2)}</small></div>
                        <div class="quantity-control">
                            <button class="btn btn-sm" onclick="changeQty('${item.id}', -1)">-</button>
                            <span>${item.quantidade}</span>
                            <button class="btn btn-sm" onclick="changeQty('${item.id}', 1)">+</button>
                        </div>
                    </div>`;
            });
        }
        cartTotal.innerText = `R$ ${total.toFixed(2)}`;
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function addToCart(product) {
        const item = cart.find(i => i.id === product.id);
        if (item) item.quantidade++; else cart.push(product);
        updateCartUI();
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Adicionado!', showConfirmButton: false, timer: 1000 });
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.quantidade += delta;
            if (item.quantidade <= 0) cart = cart.filter(i => i.id !== id);
        }
        updateCartUI();
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-add-cart');
        if (btn) {
            addToCart({ id: btn.dataset.id, nome: btn.dataset.nome, preco: parseFloat(btn.dataset.preco), quantidade: 1 });
        }
    });

    // Filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelector('.filter-btn.active').classList.remove('active');
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            document.querySelectorAll('.product-item').forEach(item => {
                item.style.display = (filter === 'all' || item.dataset.category === filter) ? 'block' : 'none';
            });
        });
    });

    // Checkout WhatsApp
    document.getElementById('checkout-btn').addEventListener('click', () => {
        let msg = "Novo Pedido:\n";
        cart.forEach(i => msg += `${i.quantidade}x ${i.nome}\n`);
        msg += `Total: ${document.getElementById('cart-total').innerText}`;
        window.open(`https://wa.me/5500000000000?text=${encodeURIComponent(msg)}`, '_blank');
    });

    updateCartUI();
</script>
</body>
</html>