<?php
include 'templates/header.php';
include 'config/conexao.php';
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 mb-0">Administrar Produtos</h1>
        <a href="produto_form.php" class="btn btn-danger">
            <i class="bi bi-plus-circle"></i> Novo Produto
        </a>
    </div>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['mensagem']) ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-dark table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.id_produto, p.nome, p.preco, p.estoque, c.nome AS categoria
                        FROM produtos p
                        LEFT JOIN produto_categoria pc ON pc.id_produto = p.id_produto
                        LEFT JOIN categorias c ON c.id_categoria = pc.id_categoria
                        ORDER BY p.nome";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0):
                    while ($produto = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td>#<?= (int) $produto['id_produto'] ?></td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td><?= htmlspecialchars($produto['categoria'] ?? 'Sem categoria') ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= (int) $produto['estoque'] ?></td>
                        <td>
                            <a href="produto_form.php?id=<?= $produto['id_produto'] ?>" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="produto_excluir.php?id=<?= $produto['id_produto'] ?>"
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Nenhum produto cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
