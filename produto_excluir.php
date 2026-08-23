<?php
session_status() === PHP_SESSION_NONE && session_start();
include 'config/conexao.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    $_SESSION['mensagem'] = "Produto inválido.";
    header("Location: admin_produtos.php");
    exit;
}

// Regra de exclusão: não permite excluir um produto que já foi vendido em algum pedido
$stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM itens_pedido WHERE id_produto = ?");
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$totalVendas = $stmtCheck->get_result()->fetch_assoc()['total'];
$stmtCheck->close();

if ($totalVendas > 0) {
    $_SESSION['mensagem'] = "Não é possível excluir este produto: ele já foi vendido em $totalVendas pedido(s). Considere zerar o estoque em vez de excluir.";
} else {
    // Remove primeiro o vínculo de categoria (evita erro de chave estrangeira)
    $stmtCat = $conn->prepare("DELETE FROM produto_categoria WHERE id_produto = ?");
    $stmtCat->bind_param("i", $id);
    $stmtCat->execute();
    $stmtCat->close();

    $stmt = $conn->prepare("DELETE FROM produtos WHERE id_produto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem'] = "Produto removido com sucesso.";
}

header("Location: admin_produtos.php");
exit;
