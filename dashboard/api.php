<?php

// Define que a resposta será do tipo JSON e aceita requisições de outras origens (CORS)
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once 'config.php';

try {
    $sql = "SELECT
                p.id_produto AS produto_id,
                p.nome AS produto,
                p.preco,
                p.estoque,
                p.genero,
                c.nome AS categoria,
                COALESCE(SUM(ip.quantidade), 0) AS quantidade_vendida
            FROM produtos p
            LEFT JOIN produto_categoria pc ON pc.id_produto = p.id_produto
            LEFT JOIN categorias c ON c.id_categoria = pc.id_categoria
            LEFT JOIN itens_pedido ip ON ip.id_produto = p.id_produto
            GROUP BY p.id_produto, p.nome, p.preco, p.estoque, p.genero, c.nome
            ORDER BY c.nome ASC, p.nome ASC";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $dados = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao executar consulta no banco de dados"]);
}
