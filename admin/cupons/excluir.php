<?php
session_status() === PHP_SESSION_NONE && session_start();
include __DIR__ . '/../../config/conexao.php';

$id       = isset($_GET['id']) ? (int) $_GET['id'] : null;
$reativar = isset($_GET['reativar']);

if (!$id) {
    $_SESSION['mensagem'] = "Cupom inválido.";
    header("Location: /darkmoon-store/admin/gerenciar-cupons.php");
    exit;
}

// Regra de exclusão: não apagamos o cupom definitivamente, apenas desativamos.
// Isso preserva o histórico de cupons já criados/usados e evita que o código
// seja reaproveitado por engano no futuro.
$novoStatus = $reativar ? 1 : 0;

$stmt = $conn->prepare("UPDATE cupons SET ativo = ? WHERE id_cupom = ?");
$stmt->bind_param("ii", $novoStatus, $id);
$stmt->execute();
$linhasAfetadas = $stmt->affected_rows;
$stmt->close();

if ($linhasAfetadas === 0) {
    $_SESSION['mensagem'] = "Cupom não encontrado.";
} else {
    $_SESSION['mensagem'] = $reativar
        ? "Cupom reativado com sucesso."
        : "Cupom desativado com sucesso. Ele não poderá mais ser usado na loja, mas o histórico foi preservado.";
}

header("Location: /darkmoon-store/admin/gerenciar-cupons.php");
exit;
