<?php
include 'templates/header.php';
include 'config/conexao.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$usuario = [
    'nome'     => '',
    'email'    => '',
    'endereco' => '',
];

if ($id) {
    $stmt = $conn->prepare("SELECT id_usuario, nome, email, endereco FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $encontrado = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$encontrado) {
        $_SESSION['mensagem'] = "Usuário não encontrado.";
        header("Location: admin_usuarios.php");
        exit;
    }

    $usuario = $encontrado;
}
?>

<div class="container mt-5 mb-5" style="max-width: 600px;">
    <h1 class="display-6 mb-4"><?= $id ? 'Editar Usuário' : 'Novo Usuário' ?></h1>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['mensagem']) ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <form method="POST" action="usuario_salvar.php">
        <?php if ($id): ?>
            <input type="hidden" name="id_usuario" value="<?= $id ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Nome completo</label>
            <input type="text" name="nome" required maxlength="150"
                   value="<?= htmlspecialchars($usuario['nome']) ?>"
                   class="form-control bg-dark text-light border-secondary">
        </div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" required maxlength="150"
                   value="<?= htmlspecialchars($usuario['email']) ?>"
                   class="form-control bg-dark text-light border-secondary">
        </div>

        <div class="mb-3">
            <label class="form-label">Endereço</label>
            <input type="text" name="endereco" maxlength="255"
                   value="<?= htmlspecialchars($usuario['endereco'] ?? '') ?>"
                   class="form-control bg-dark text-light border-secondary">
        </div>

        <div class="mb-4">
            <label class="form-label">
                <?= $id ? 'Nova senha' : 'Senha' ?>
            </label>
            <input type="password" name="senha" <?= $id ? '' : 'required' ?>
                   class="form-control bg-dark text-light border-secondary"
                   placeholder="<?= $id ? 'Deixe em branco para manter a senha atual' : '' ?>">
            <?php if ($id): ?>
                <small class="text-secondary">Preencha apenas se quiser alterar a senha.</small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-danger">
            <?= $id ? 'Salvar Alterações' : 'Cadastrar Usuário' ?>
        </button>
        <a href="admin_usuarios.php" class="btn btn-outline-light">Cancelar</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
