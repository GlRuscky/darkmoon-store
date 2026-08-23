<?php
include 'templates/header.php';
include 'config/conexao.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$cupom = [
    'codigo'              => '',
    'percentual_desconto' => '',
];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM cupons WHERE id_cupom = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $encontrado = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$encontrado) {
        $_SESSION['mensagem'] = "Cupom não encontrado.";
        header("Location: admin_cupons.php");
        exit;
    }

    $cupom = $encontrado;
}
?>

<div class="container mt-5 mb-5" style="max-width: 600px;">
    <h1 class="display-6 mb-4"><?= $id ? 'Editar Cupom' : 'Novo Cupom' ?></h1>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['mensagem']) ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <form method="POST" action="cupom_salvar.php">
        <?php if ($id): ?>
            <input type="hidden" name="id_cupom" value="<?= $id ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Código do cupom</label>
            <input type="text" name="codigo" required maxlength="50"
                   style="text-transform: uppercase;"
                   value="<?= htmlspecialchars($cupom['codigo']) ?>"
                   placeholder="Ex: DARK10"
                   class="form-control bg-dark text-light border-secondary">
        </div>

        <div class="mb-4">
            <label class="form-label">Percentual de desconto (%)</label>
            <input type="number" name="percentual_desconto" min="1" max="100" step="1" required
                   value="<?= htmlspecialchars($cupom['percentual_desconto']) ?>"
                   class="form-control bg-dark text-light border-secondary">
        </div>

        <button type="submit" class="btn btn-danger">
            <?= $id ? 'Salvar Alterações' : 'Cadastrar Cupom' ?>
        </button>
        <a href="admin_cupons.php" class="btn btn-outline-light">Cancelar</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
