<?php
session_status() === PHP_SESSION_NONE && session_start();
include 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_produtos.php");
    exit;
}

$id          = isset($_POST['id_produto']) ? (int) $_POST['id_produto'] : null;
$nome        = trim($_POST['nome'] ?? '');
$descricao   = trim($_POST['descricao'] ?? '');
$preco       = (float) ($_POST['preco'] ?? 0);
$estoque     = (int) ($_POST['estoque'] ?? 0);
$genero      = $_POST['genero'] ?? 'Unissex';
$imagem      = trim($_POST['imagem'] ?? '');
$idCategoria = isset($_POST['id_categoria']) && $_POST['id_categoria'] !== '' ? (int) $_POST['id_categoria'] : null;

// Validação básica
if ($nome === '' || $preco < 0 || $estoque < 0 || !$idCategoria) {
    $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios corretamente.";
    header("Location: produto_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

if ($id) {
    // ===== UPDATE =====
    $stmt = $conn->prepare(
        "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, genero = ?, imagem = ? WHERE id_produto = ?"
    );
    $stmt->bind_param("ssdissi", $nome, $descricao, $preco, $estoque, $genero, $imagem, $id);
    $stmt->execute();
    $stmt->close();

    // Atualiza a categoria (remove vínculo antigo e insere o novo)
    $stmtDel = $conn->prepare("DELETE FROM produto_categoria WHERE id_produto = ?");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();
    $stmtDel->close();

    $stmtCat = $conn->prepare("INSERT INTO produto_categoria (id_produto, id_categoria) VALUES (?, ?)");
    $stmtCat->bind_param("ii", $id, $idCategoria);
    $stmtCat->execute();
    $stmtCat->close();

    $_SESSION['mensagem'] = "Produto atualizado com sucesso.";
} else {
    // ===== INSERT =====
    $stmt = $conn->prepare(
        "INSERT INTO produtos (nome, descricao, preco, estoque, genero, imagem) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssdiss", $nome, $descricao, $preco, $estoque, $genero, $imagem);
    $stmt->execute();
    $novoId = $stmt->insert_id;
    $stmt->close();

    $stmtCat = $conn->prepare("INSERT INTO produto_categoria (id_produto, id_categoria) VALUES (?, ?)");
    $stmtCat->bind_param("ii", $novoId, $idCategoria);
    $stmtCat->execute();
    $stmtCat->close();

    $_SESSION['mensagem'] = "Produto cadastrado com sucesso.";
}

header("Location: admin_produtos.php");
exit;
