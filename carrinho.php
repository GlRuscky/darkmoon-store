<?php 
include 'templates/header.php'; 
include 'config/conexao.php'; 

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_GET['adicionar'])) {
    $id = (int) $_GET['adicionar'];
    $_SESSION['carrinho'][$id] = ($_SESSION['carrinho'][$id] ?? 0) + 1;
}

if (isset($_GET['remover'])) {
    $id = (int) $_GET['remover'];
    unset($_SESSION['carrinho'][$id]);
}
?>

<div class="container mt-5 mb-5">
    <h1 class="text-center display-5 mb-4">Seu Carrinho</h1>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <p class="text-center">Seu carrinho está vazio.</p>
    <?php else: ?>
        <?php $total = 0; ?>

        <?php foreach ($_SESSION['carrinho'] as $id => $qtd): ?>
            <?php
            $result  = $conn->query("SELECT * FROM produtos WHERE id_produto = $id");
            $produto = $result->fetch_assoc();
            if (!$produto) continue;
            $subtotal = $produto['preco'] * $qtd;
            $total   += $subtotal;
            ?>
            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary py-3">
                <div>
                    <strong><?= htmlspecialchars($produto['nome']) ?></strong><br>
                    <span class="text-secondary">Quantidade: <?= $qtd ?> — R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
                <a href="carrinho.php?remover=<?= $id ?>" class="btn btn-outline-light btn-sm">Remover</a>
            </div>
        <?php endforeach; ?>

        <h4 class="text-danger text-end mt-4">Total: R$ <?= number_format($total, 2, ',', '.') ?></h4>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
