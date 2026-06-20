<?php 
include 'templates/header.php'; 
include 'config/conexao.php'; 
include 'funcoes.php'; 
?>

<?php
// TESTE - apagar depois
$teste = $conn->query("SELECT p.nome FROM produtos p INNER JOIN produto_categoria pc ON p.id_produto = pc.id_produto WHERE pc.id_categoria = 4");
echo "<pre style='color:lime'>";
echo "Acessórios encontrados: " . $teste->num_rows . "\n";
while($r = $teste->fetch_assoc()) echo $r['nome'] . "\n";
echo "</pre>";
?>

<div class="container mt-5">

    <!-- Hero -->
    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold text-white">DarkMoon Store</h1>
        <p class="lead fs-4">Loja especializada em moda gótica, alternativa e darkwear.</p>
    </div>

    <!-- DESTAQUES -->
    <h2 id="destaque-titulo" class="text-center display-6 mb-4">PRODUTOS EM DESTAQUE</h2>
    <?php
    $sql = "SELECT * FROM produtos ORDER BY RAND() LIMIT 8";
    $result = $conn->query($sql);
    echo '<div class="row">';
    while ($produto = $result->fetch_assoc()) {
        exibirProdutoCard($produto);
    }
    echo '</div>';
    ?>

    <hr class="my-5 border-danger">

    <!-- CAMISAS -->
    <h2 class="text-center display-6 mb-4">CAMISAS</h2>
    <?php
    $sql = "SELECT p.* FROM produtos p 
            INNER JOIN produto_categoria pc ON p.id_produto = pc.id_produto 
            WHERE pc.id_categoria = 1 LIMIT 8";
    $result = $conn->query($sql);
    echo '<div class="row">';
    while ($produto = $result->fetch_assoc()) {
        exibirProdutoCard($produto);
    }
    echo '</div>';
    ?>

    <hr class="my-5 border-danger">

    <!-- VESTIDOS -->
    <h2 class="text-center display-6 mb-4">VESTIDOS</h2>
    <?php
    $sql = "SELECT p.* FROM produtos p 
            INNER JOIN produto_categoria pc ON p.id_produto = pc.id_produto 
            WHERE pc.id_categoria = 2 LIMIT 8";
    $result = $conn->query($sql);
    echo '<div class="row">';
    while ($produto = $result->fetch_assoc()) {
        exibirProdutoCard($produto);
    }
    echo '</div>';
    ?>

    <hr class="my-5 border-danger">

    <!-- BOTAS -->
    <h2 class="text-center display-6 mb-4">BOTAS</h2>
    <?php
    $sql = "SELECT p.* FROM produtos p 
            INNER JOIN produto_categoria pc ON p.id_produto = pc.id_produto 
            WHERE pc.id_categoria = 3 LIMIT 8";
    $result = $conn->query($sql);
    echo '<div class="row">';
    while ($produto = $result->fetch_assoc()) {
        exibirProdutoCard($produto);
    }
    echo '</div>';
    ?>

    <hr class="my-5 border-danger">

    <!-- ACESSÓRIOS -->
    <h2 class="text-center display-6 mb-4">ACESSÓRIOS</h2>
    <?php
    $sql = "SELECT p.* FROM produtos p 
            INNER JOIN produto_categoria pc ON p.id_produto = pc.id_produto 
            WHERE pc.id_categoria = 4 LIMIT 8";
    $result = $conn->query($sql);
    echo '<div class="row">';
    while ($produto = $result->fetch_assoc()) {
        exibirProdutoCard($produto);
    }
    echo '</div>';
    ?>

</div>

<?php include 'templates/footer.php'; ?>
