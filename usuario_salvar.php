<?php
session_status() === PHP_SESSION_NONE && session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_usuarios.php");
    exit;
}

$id       = isset($_POST['id_usuario']) ? (int) $_POST['id_usuario'] : null;
$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$senha    = $_POST['senha'] ?? '';

if ($nome === '' || $email === '') {
    $_SESSION['mensagem'] = "Nome e e-mail são obrigatórios.";
    header("Location: usuario_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

if (!$id && $senha === '') {
    $_SESSION['mensagem'] = "A senha é obrigatória para novos usuários.";
    header("Location: usuario_form.php");
    exit;
}


$stmtCheckEmail = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
$idParaCheck = $id ?? 0;
$stmtCheckEmail->bind_param("si", $email, $idParaCheck);
$stmtCheckEmail->execute();
$emailEmUso = $stmtCheckEmail->get_result()->fetch_assoc();
$stmtCheckEmail->close();

if ($emailEmUso) {
    $_SESSION['mensagem'] = "Esse e-mail já está em uso por outro usuário.";
    header("Location: usuario_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

if ($id) {

    if ($senha !== '') {

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, endereco = ?, senha = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssssi", $nome, $email, $endereco, $senhaHash, $id);
    } else {

        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, endereco = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $nome, $email, $endereco, $id);
    }
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem'] = "Usuário atualizado com sucesso.";
} else {

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, endereco) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senhaHash, $endereco);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem'] = "Usuário cadastrado com sucesso.";
}

header("Location: admin_usuarios.php");
exit;
