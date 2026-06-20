<?php 
include 'config/conexao.php';
include 'funcoes.php';

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $endereco = trim($_POST['endereco'] ?? '');

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {
        $nomeEscaped = $conn->real_escape_string($nome);
        $emailEscaped = $conn->real_escape_string($email);
        $enderecoEscaped = $conn->real_escape_string($endereco);
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha, endereco) 
                VALUES ('$nomeEscaped', '$emailEscaped', '$senhaHash', '$enderecoEscaped')";

        if ($conn->query($sql)) {
            $sucesso = true;
        } else {
            $erro = "Erro ao cadastrar. Talvez esse e-mail já esteja em uso.";
        }
    }
}

include 'templates/header.php'; 
?>

<div class="container mt-5 mb-5" style="max-width: 500px;">
    <h1 class="text-center display-5 mb-4">Criar Conta</h1>

    <?php if ($sucesso): ?>
        <div class="alert alert-success">Cadastro feito com sucesso! 🎉</div>
    <?php endif; ?>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="cadastro.php">
        <div class="mb-3">
            <label class="form-label">Nome completo</label>
            <input type="text" name="nome" class="form-control bg-dark text-light border-danger" required>
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control bg-dark text-light border-danger" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Endereço (opcional)</label>
            <input type="text" name="endereco" class="form-control bg-dark text-light border-danger">
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control bg-dark text-light border-danger" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Cadastrar</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>