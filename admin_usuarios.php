<?php
include 'templates/header.php';
include 'config/conexao.php';
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 mb-0">Administrar Usuários</h1>
        <a href="usuario_form.php" class="btn btn-danger">
            <i class="bi bi-person-plus"></i> Novo Usuário
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
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>Cadastrado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id_usuario, nome, email, endereco, data_cadastro FROM usuarios ORDER BY nome";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0):
                    while ($usuario = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td>#<?= (int) $usuario['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['endereco'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($usuario['data_cadastro'])) ?></td>
                        <td>
                            <a href="usuario_form.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="usuario_excluir.php?id=<?= $usuario['id_usuario'] ?>"
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Nenhum usuário cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
