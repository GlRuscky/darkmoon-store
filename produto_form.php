<?php
include 'templates/header.php';
include 'config/conexao.php';

// Se veio um id na URL, estamos editando; senão, é um cadastro novo
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$produto = [
    'nome'      => '',
    'descricao' => '',
    'preco'     => '',
    'estoque'   => '',
    'genero'    => 'Unissex',
    'imagem'    => '',
];
$categoriaSelecionada = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id_produto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $encontrado = $resultado->fetch_assoc();

    if (!$encontrado) {
        $_SESSION['mensagem'] = "Produto não encontrado.";
        header("Location: admin_produtos.php");
        exit;
    }

    $produto = $encontrado;
    $stmt->close();

    // Busca a categoria atual do produto
    $stmtCat = $conn->prepare("SELECT id_categoria FROM produto_categoria WHERE id_produto = ? LIMIT 1");
    $stmtCat->bind_param("i", $id);
    $stmtCat->execute();
    $resCat = $stmtCat->get_result();
    $catRow = $resCat->fetch_assoc();
    $categoriaSelecionada = $catRow['id_categoria'] ?? null;
    $stmtCat->close();
}

$categorias = $conn->query("SELECT * FROM categorias ORDER BY nome");
?>

<div class="container mt-5 mb-5" style="max-width: 700px;">
    <h1 class="display-6 mb-4"><?= $id ? 'Editar Produto' : 'Novo Produto' ?></h1>

    <form method="POST" action="produto_salvar.php">
        <?php if ($id): ?>
            <input type="hidden" name="id_produto" value="<?= $id ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" required maxlength="150"
                   value="<?= htmlspecialchars($produto['nome']) ?>"
                   class="form-control bg-dark text-light border-secondary">
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" rows="3"
                      class="form-control bg-dark text-light border-secondary"><?= htmlspecialchars($produto['descricao'] ?? '') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Preço (R$)</label>
                <input type="number" name="preco" step="0.01" min="0" required
                       value="<?= htmlspecialchars($produto['preco']) ?>"
                       class="form-control bg-dark text-light border-secondary">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Estoque</label>
                <input type="number" name="estoque" min="0" required
                       value="<?= htmlspecialchars($produto['estoque']) ?>"
                       class="form-control bg-dark text-light border-secondary">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Gênero</label>
                <select name="genero" class="form-select bg-dark text-light border-secondary">
                    <?php foreach (['Masculino', 'Feminino', 'Unissex'] as $opcao): ?>
                        <option value="<?= $opcao ?>" <?= $produto['genero'] === $opcao ? 'selected' : '' ?>>
                            <?= $opcao ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Categoria</label>
                <select name="id_categoria" required class="form-select bg-dark text-light border-secondary">
                    <option value="">Selecione...</option>
                    <?php while ($cat = $categorias->fetch_assoc()): ?>
                        <option value="<?= $cat['id_categoria'] ?>"
                            <?= $categoriaSelecionada == $cat['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Nome do arquivo de imagem</label>
            <input type="text" name="imagem" placeholder="ex: camisa-nova.webp"
                   value="<?= htmlspecialchars($produto['imagem'] ?? '') ?>"
                   class="form-control bg-dark text-light border-secondary">
            <small class="text-secondary">Deve corresponder a um arquivo já existente na pasta images/</small>
        </div>

        <button type="submit" class="btn btn-danger">
            <?= $id ? 'Salvar Alterações' : 'Cadastrar Produto' ?>
        </button>
        <a href="admin_produtos.php" class="btn btn-outline-light">Cancelar</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
