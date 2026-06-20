<?php 
include 'templates/header.php'; 
include 'config/conexao.php'; 
include 'funcoes.php'; 
?>

<div class="container mt-5 mb-5">

    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-white">Promoções</h1>
        <p class="lead">Tem cupom? Aplica aqui e veja o desconto na hora.</p>
    </div>

    <?php
    // Lê o cupom digitado e valida usando a função validarCupom() de funcoes.php
    $cupomDigitado    = $_GET['cupom'] ?? '';
    $descontoAplicado = false;

    if (!empty($cupomDigitado)) {
        $descontoAplicado = validarCupom($cupons, $cupomDigitado);
    }

    // Lê a faixa de preço
    $precoMin = $_GET['preco_min'] ?? '';
    $precoMax = $_GET['preco_max'] ?? '';

    // Busca todos os produtos do banco e armazena em array PHP
    $sql = "SELECT * FROM produtos";
    $result = $conn->query($sql);

    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }

    // Filtra por faixa de preço em memória usando filtrarPorFaixaPreco() de funcoes.php
    if (is_numeric($precoMin) && is_numeric($precoMax)) {
        $produtos = filtrarPorFaixaPreco($produtos, (float) $precoMin, (float) $precoMax);
    }

    // Embaralha e limita a 8 produtos para exibição
    shuffle($produtos);
    $produtos = array_slice($produtos, 0, 8);
    ?>

    <!-- Formulário de cupom -->
    <form method="GET" action="promocoes.php" class="d-flex justify-content-center gap-2 mb-3">
        <input type="text" name="cupom" placeholder="Digite seu cupom (ex: DARK10)"
               value="<?= htmlspecialchars($cupomDigitado) ?>"
               class="form-control bg-dark text-light border-danger" style="max-width: 300px;">
        <button type="submit" class="btn btn-danger">Aplicar Cupom</button>
    </form>

    <!-- Formulário de faixa de preço -->
    <form method="GET" action="promocoes.php" class="d-flex justify-content-center align-items-center gap-2 mb-4">
        <input type="hidden" name="cupom" value="<?= htmlspecialchars($cupomDigitado) ?>">
        <label class="text-light small mb-0">Preço:</label>
        <input type="number" name="preco_min" placeholder="Mín" value="<?= htmlspecialchars($precoMin) ?>"
               class="form-control bg-dark text-light border-danger" style="width: 100px;">
        <span class="text-light">até</span>
        <input type="number" name="preco_max" placeholder="Máx" value="<?= htmlspecialchars($precoMax) ?>"
               class="form-control bg-dark text-light border-danger" style="width: 100px;">
        <button type="submit" class="btn btn-outline-light">Filtrar</button>
    </form>

    <!-- Feedback visual do cupom -->
    <?php if (!empty($cupomDigitado)): ?>
        <?php if ($descontoAplicado !== false): ?>
            <p class="text-center text-success mb-5">
                ✅ Cupom válido! <strong><?= $descontoAplicado ?>% OFF</strong> aplicado abaixo.
            </p>
        <?php else: ?>
            <p class="text-center text-danger mb-5">
                ❌ Cupom inválido ou expirado.
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row">
        <?php if (empty($produtos)): ?>
            <p class="text-center">Nenhum produto encontrado nessa faixa de preço.</p>
        <?php endif; ?>

        <?php foreach ($produtos as $produto): ?>
            <?php
            $precoOriginal = $produto['preco'];
            // Aplica o desconto usando aplicarDesconto() de funcoes.php
            $precoFinal = $descontoAplicado !== false
                ? aplicarDesconto($precoOriginal, $descontoAplicado)
                : $precoOriginal;
            ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 bg-secondary text-white border-0 shadow-sm">
                    <img src="images/<?= htmlspecialchars(trim($produto['imagem'])) ?>" 
                         class="card-img-top" style="height: 220px; object-fit: cover;"
                         onerror="this.src='images/sem-imagem.jpg';">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>

                        <?php if ($descontoAplicado !== false): ?>
                            <p class="mb-0 text-decoration-line-through text-secondary">
                                R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                            </p>
                            <h4 class="text-danger fw-bold">
                                R$ <?= number_format($precoFinal, 2, ',', '.') ?>
                            </h4>
                        <?php else: ?>
                            <h4 class="text-danger fw-bold">
                                R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                            </h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include 'templates/footer.php'; ?>
