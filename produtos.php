<?php 
include 'templates/header.php'; 
include 'config/conexao.php'; 
include 'funcoes.php'; 
?>

<div class="container mt-5 mb-5">

    <h1 class="text-center display-5 mb-4">Nossos Produtos</h1>

    <?php
    // Lê os filtros enviados via URL (?busca=...&categoria=...&preco_min=...&preco_max=...)
    $busca     = $_GET['busca']     ?? '';
    $categoria = $_GET['categoria'] ?? '';
    $precoMin  = $_GET['preco_min'] ?? '';
    $precoMax  = $_GET['preco_max'] ?? '';

    // Mapa nome (URL) -> id_categoria no banco
    // Usar ID evita problemas de encoding com caracteres acentuados
    $categoriasMap = [
        'Camisas'    => 1,
        'Vestidos'   => 2,
        'Botas'      => 3,
        'Acessorios' => 4,
    ];

    // Query base com JOIN para permitir filtro por categoria
    $sql = "SELECT DISTINCT p.* FROM produtos p
            LEFT JOIN produto_categoria pc ON p.id_produto = pc.id_produto
            WHERE 1=1";

    // Filtro de busca por nome ou descrição (LIKE)
    if (!empty($busca)) {
        $buscaEscaped = $conn->real_escape_string($busca);
        $sql .= " AND (p.nome LIKE '%$buscaEscaped%' OR p.descricao LIKE '%$buscaEscaped%')";
    }

    // Filtro de categoria usando id numérico
    if (!empty($categoria) && isset($categoriasMap[$categoria])) {
        $idCategoria = $categoriasMap[$categoria];
        $sql .= " AND pc.id_categoria = $idCategoria";
    }

    // Filtro de faixa de preço
    if (is_numeric($precoMin)) {
        $sql .= " AND p.preco >= " . floatval($precoMin);
    }
    if (is_numeric($precoMax)) {
        $sql .= " AND p.preco <= " . floatval($precoMax);
    }

    $sql .= " ORDER BY p.nome ASC";

    $result = $conn->query($sql);
    $totalProdutos = $result->num_rows;
    ?>

    <!-- Botões de filtro por categoria -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        <a href="produtos.php" class="btn btn-outline-light <?= empty($categoria) ? 'active' : '' ?>">Todos</a>
        <a href="produtos.php?categoria=Camisas"    class="btn btn-outline-light <?= $categoria === 'Camisas'    ? 'active' : '' ?>">Camisas</a>
        <a href="produtos.php?categoria=Vestidos"   class="btn btn-outline-light <?= $categoria === 'Vestidos'   ? 'active' : '' ?>">Vestidos</a>
        <a href="produtos.php?categoria=Botas"      class="btn btn-outline-light <?= $categoria === 'Botas'      ? 'active' : '' ?>">Botas</a>
        <a href="produtos.php?categoria=Acessorios" class="btn btn-outline-light <?= $categoria === 'Acessorios' ? 'active' : '' ?>">Acessórios</a>
    </div>

    <!-- Filtro de faixa de preço via formulário GET -->
    <form method="GET" action="produtos.php" class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-4">
        <?php if (!empty($busca)): ?>
            <input type="hidden" name="busca" value="<?= htmlspecialchars($busca) ?>">
        <?php endif; ?>
        <?php if (!empty($categoria)): ?>
            <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
        <?php endif; ?>

        <label class="text-light small mb-0">Preço:</label>
        <input type="number" name="preco_min" min="0" step="0.01" placeholder="Mín"
               value="<?= htmlspecialchars($precoMin) ?>"
               class="form-control bg-dark text-light border-danger" style="width: 110px;">
        <span class="text-light">até</span>
        <input type="number" name="preco_max" min="0" step="0.01" placeholder="Máx"
               value="<?= htmlspecialchars($precoMax) ?>"
               class="form-control bg-dark text-light border-danger" style="width: 110px;">

        <button type="submit" class="btn btn-danger">Filtrar</button>

        <?php if (!empty($precoMin) || !empty($precoMax)): ?>
            <a href="produtos.php?busca=<?= urlencode($busca) ?>&categoria=<?= urlencode($categoria) ?>" 
               class="btn btn-outline-light btn-sm">Limpar preço</a>
        <?php endif; ?>
    </form>

    <!-- Exibe quantos produtos foram encontrados na busca -->
    <?php if (!empty($busca)): ?>
        <p class="text-center text-secondary mb-4">
            Resultados para "<strong><?= htmlspecialchars($busca) ?></strong>" 
            — <?= $totalProdutos ?> produto<?= $totalProdutos !== 1 ? 's' : '' ?> encontrado<?= $totalProdutos !== 1 ? 's' : '' ?>
        </p>
    <?php endif; ?>

    <div class="row">
        <?php if ($totalProdutos > 0): ?>
            <?php while ($produto = $result->fetch_assoc()): ?>
                <?php exibirProdutoCard($produto); ?>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-danger"></i>
                <p class="fs-5 mt-3">Nenhum produto encontrado.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include 'templates/footer.php'; ?>
