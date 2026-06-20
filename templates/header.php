<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkMoon Store - Moda Gótica</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Fontes Góticas -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=UnifrakturMaguntia&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">

<?php
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

    <!-- Navbar - Componente principal do Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-danger sticky-top">
        <div class="container">
            
            <!-- Brand com emoji -->
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                DarkMoon Store
            </a>

            <!-- Botão de menu mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
    <a class="nav-link <?= $paginaAtual === 'index.php' ? 'active' : '' ?>" href="index.php">
        <i class="bi bi-house-door"></i> Início
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= $paginaAtual === 'produtos.php' ? 'active' : '' ?>" href="produtos.php">
        <i class="bi bi-shop"></i> Produtos
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= $paginaAtual === 'promocoes.php' ? 'active' : '' ?>" href="promocoes.php">
        <i class="bi bi-tags"></i> Promoções
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= $paginaAtual === 'sobre.php' ? 'active' : '' ?>" href="sobre.php">
        <i class="bi bi-info-circle"></i> Sobre Nós
    </a>
</li>
                </ul>

                <!-- Barra de Pesquisa (Componente Form + Input Group) -->
                <form class="d-flex me-3" action="produtos.php" method="GET">
                    <div class="input-group">
                        <input type="text" name="busca" class="form-control bg-dark text-light border-danger" 
                               placeholder="Buscar produtos..." aria-label="Buscar">
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Ícones de ações (Componentes Buttons) -->
                <div class="d-flex gap-2">
                    <a href="carrinho.php" class="btn btn-outline-light position-relative">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                    <a href="cadastro.php" class="btn btn-outline-light">
                        <i class="bi bi-person-circle fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>