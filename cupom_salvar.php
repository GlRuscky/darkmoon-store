<?php
session_status() === PHP_SESSION_NONE && session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_cupons.php");
    exit;
}

$id         = isset($_POST['id_cupom']) ? (int) $_POST['id_cupom'] : null;
$codigo     = strtoupper(trim($_POST['codigo'] ?? ''));
$percentual = (float) ($_POST['percentual_desconto'] ?? 0);

if ($codigo === '' || $percentual <= 0 || $percentual > 100) {
    $_SESSION['mensagem'] = "Preencha o código e um percentual válido entre 1 e 100.";
    header("Location: cupom_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// Verifica se o código já está em uso por OUTRO cupom
$stmtCheck = $conn->prepare("SELECT id_cupom FROM cupons WHERE codigo = ? AND id_cupom != ?");
$idParaCheck = $id ?? 0;
$stmtCheck->bind_param("si", $codigo, $idParaCheck);
$stmtCheck->execute();
$codigoEmUso = $stmtCheck->get_result()->fetch_assoc();
$stmtCheck->close();

if ($codigoEmUso) {
    $_SESSION['mensagem'] = "Já existe um cupom com esse código.";
    header("Location: cupom_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

if ($id) {
    $stmt = $conn->prepare("UPDATE cupons SET codigo = ?, percentual_desconto = ? WHERE id_cupom = ?");
    $stmt->bind_param("sdi", $codigo, $percentual, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem'] = "Cupom atualizado com sucesso.";
} else {
    $stmt = $conn->prepare("INSERT INTO cupons (codigo, percentual_desconto) VALUES (?, ?)");
    $stmt->bind_param("sd", $codigo, $percentual);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem'] = "Cupom cadastrado com sucesso.";
}

header("Location: admin_cupons.php");
exit;
