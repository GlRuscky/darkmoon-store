<?php
include 'templates/header.php';
include 'config/conexao.php';
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 mb-0">Administrar Cupons</h1>
        <a href="cupom_form.php" class="btn btn-danger">
            <i class="bi bi-plus-circle"></i> Novo Cupom
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
                    <th>Código</th>
                    <th>Desconto</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM cupons ORDER BY ativo DESC, codigo";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0):
                    while ($cupom = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td>#<?= (int) $cupom['id_cupom'] ?></td>
                        <td><strong><?= htmlspecialchars($cupom['codigo']) ?></strong></td>
                        <td><?= number_format($cupom['percentual_desconto'], 0) ?>%</td>
                        <td>
                            <?php if ($cupom['ativo']): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($cupom['data_criacao'])) ?></td>
                        <td>
                            <a href="cupom_form.php?id=<?= $cupom['id_cupom'] ?>" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <?php if ($cupom['ativo']): ?>
                                <a href="cupom_excluir.php?id=<?= $cupom['id_cupom'] ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Desativar este cupom? Ele deixará de funcionar na loja.');">
                                    <i class="bi bi-x-circle"></i> Desativar
                                </a>
                            <?php else: ?>
                                <a href="cupom_excluir.php?id=<?= $cupom['id_cupom'] ?>&reativar=1"
                                   class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-check-circle"></i> Reativar
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Nenhum cupom cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
