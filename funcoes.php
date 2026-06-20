<?php
/**
 * ==================== ARRAY DE CUPONS ====================
 */
$cupons = [
    'DARK10'      => 10,
    'MOON15'      => 15,
    'GOTICA20'    => 20,
    'BLACKFRIDAY' => 30,
];

/**
 * ==================== VALIDAR CUPOM ====================
 */
function validarCupom($cupons, $codigo) {
    $codigo = strtoupper(trim($codigo));

    if (empty($codigo)) {
        return false;
    }

    return array_key_exists($codigo, $cupons) ? $cupons[$codigo] : false;
}

/**
 * ==================== APLICAR DESCONTO ====================
 */
function aplicarDesconto($preco, $percentual) {
    if ($preco <= 0 || $percentual < 0 || $percentual > 100) {
        return $preco;
    }

    return round($preco - ($preco * ($percentual / 100)), 2);
}

/**
 * ==================== FILTRAR PRODUTOS POR FAIXA DE PREÇO ====================
 */
function filtrarPorFaixaPreco($produtos, $min, $max) {
    $resultado = [];

    foreach ($produtos as $produto) {
        if ($produto['preco'] >= $min && $produto['preco'] <= $max) {
            $resultado[] = $produto;
        }
    }

    return $resultado;
}

/**
 * ==================== EXIBIR CARD DE PRODUTO ====================
 */
function exibirProdutoCard($produto) {
    $imagem = !empty($produto['imagem']) ? 'images/' . trim($produto['imagem']) : 'images/sem-imagem.jpg';

    echo '
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card h-100 bg-secondary text-white border-0 shadow-sm overflow-hidden">
            <img src="' . htmlspecialchars($imagem) . '" 
                 class="card-img-top" 
                 alt="' . htmlspecialchars($produto['nome']) . '" 
                 style="height: 280px; object-fit: cover;"
                 onerror="this.src=\'images/sem-imagem.jpg\';">
            
            <div class="card-body d-flex flex-column p-3">
                <h5 class="card-title fw-bold mb-2">' . htmlspecialchars($produto['nome']) . '</h5>
                <p class="card-text small flex-grow-1">' . htmlspecialchars(substr($produto['descricao'] ?? '', 0, 70)) . '...</p>
                
                <div class="mt-auto pt-2">
                    <h4 class="text-danger fw-bold mb-3">R$ ' . number_format($produto['preco'], 2, ',', '.') . '</h4>
                    <a href="produto.php?id=' . $produto['id_produto'] . '" class="btn btn-outline-light w-100 mb-2">Ver Detalhes</a>
                    <a href="carrinho.php?adicionar=' . $produto['id_produto'] . '" class="btn btn-danger w-100">
                        <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                    </a>
                </div>
            </div>
        </div>
    </div>';
}
?>
