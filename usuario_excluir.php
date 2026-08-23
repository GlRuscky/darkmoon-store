<?php
session_status() === PHP_SESSION_NONE && session_start();
include 'config/conexao.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    $_SESSION['mensagem'] = "Usuário inválido.";
    header("Location: admin_usuarios.php");
    exit;
}

$stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM pedidos WHERE id_usuario = ?");
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$totalPedidos = $stmtCheck->get_result()->fetch_assoc()['total'];
$stmtCheck->close();

if ($totalPedidos > 0) {
    $_SESSION['mensagem'] = "Não é possível excluir este usuário: ele possui $totalPedidos pedido(s) registrado(s) no histórico.";
} else {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem'] = "Usuário removido com sucesso.";
}

header("Location: admin_usuarios.php");
exit;
