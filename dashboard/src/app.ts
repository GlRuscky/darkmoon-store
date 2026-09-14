// ====================================================================
// PASSO 1: FUNÇÃO PARA BUSCAR OS DADOS DO SERVIDOR (API PHP)
// ====================================================================
async function carregarDashboard(): Promise<void> {
    try {
        // Faz a requisição HTTP GET para o arquivo api.php
        const resposta = await fetch('api.php');

        // Se o servidor retornar um erro (ex: status 404 ou 500)
        if (!resposta.ok) {
            throw new Error(`Erro na requisição: Status ${resposta.status}`);
        }

        // Converte a resposta recebida em um array de objetos do tipo Produto
        const produtos: Produto[] = await resposta.json();

        // Se não houver produtos cadastrados, trata o cenário vazio explicitamente
        if (produtos.length === 0) {
            exibirEstadoVazio();
            return;
        }

        // Atualiza a tela enviando os produtos para os Cards e para a Tabela
        atualizarCards(produtos);
        exibirTabela(produtos);
        exibirEstoqueCritico(produtos);

    } catch (erro) {
        console.error('Falha ao carregar produtos:', erro);
        exibirErro('Não foi possível carregar os dados da API. Verifique a conexão com o banco.');
    }
}

// ====================================================================
// PASSO 2: FUNÇÃO PARA CALCULAR AS ESTATÍSTICAS E ATUALIZAR OS CARDS
// ====================================================================
function atualizarCards(produtos: Produto[]): void {
    if (produtos.length === 0) return;

    // --- 1. FATURAMENTO TOTAL (reduce) ---
    // Soma preco * quantidade_vendida de todos os produtos
    const faturamentoTotal: number = produtos.reduce((acumulador, item) => {
        return acumulador + Number(item.preco) * Number(item.quantidade_vendida);
    }, 0);

    // --- 2. PRODUTO MAIS VENDIDO (ranking por contagem) ---
    const produtoMaisVendido: Produto | null = encontrarProdutoMaisVendido(produtos);

    // --- 3. PRODUTOS COM ESTOQUE CRÍTICO (filter) ---
    const estoqueCritico: Produto[] = filtrarEstoqueCritico(produtos);

    // --- ATUALIZAÇÃO DO HTML (DOM), sempre checando se o elemento existe ---

    const elFaturamento = document.getElementById('card-faturamento');
    if (elFaturamento) {
        elFaturamento.innerText = formatarMoeda(faturamentoTotal);
    }

    const elDestaque = document.getElementById('card-produto-destaque');
    if (elDestaque) {
        elDestaque.innerText = produtoMaisVendido
            ? `${produtoMaisVendido.produto} (${produtoMaisVendido.quantidade_vendida} un. vendidas)`
            : 'Nenhuma venda registrada';
    }

    const elEstoqueCritico = document.getElementById('card-estoque-critico');
    if (elEstoqueCritico) {
        elEstoqueCritico.innerText = estoqueCritico.length.toString();
    }
}

// ====================================================================
// FUNÇÃO: ENCONTRAR O PRODUTO MAIS VENDIDO (estrutura de contagem/ranking)
// ====================================================================
function encontrarProdutoMaisVendido(produtos: Produto[]): Produto | null {
    // Estrutura chave-valor para relacionar id -> quantidade vendida
    const contagem: Record<number, number> = {};

    produtos.forEach((item) => {
        contagem[item.produto_id] = Number(item.quantidade_vendida);
    });

    let idMaisVendido: number | null = null;
    let maiorQuantidade = 0;

    for (const idStr in contagem) {
        const id = Number(idStr);
        if (contagem[id] > maiorQuantidade) {
            maiorQuantidade = contagem[id];
            idMaisVendido = id;
        }
    }

    // Edge case: ninguém vendeu nada ainda
    if (idMaisVendido === null) {
        return null;
    }

    return produtos.find((p) => p.produto_id === idMaisVendido) ?? null;
}

// ====================================================================
// FUNÇÃO: FILTRAR PRODUTOS COM ESTOQUE CRÍTICO (filter)
// ====================================================================
function filtrarEstoqueCritico(produtos: Produto[], limite: number = 5): Produto[] {
    return produtos.filter((item) => Number(item.estoque) < limite);
}

// ====================================================================
// PASSO 3: FUNÇÃO PARA PREENCHER A TABELA DE PRODUTOS NO HTML (map)
// ====================================================================
function exibirTabela(produtos: Produto[]): void {
    const tbody = document.getElementById('tabela-produtos-body');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (produtos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">Nenhum produto cadastrado.</td></tr>';
        return;
    }

    // Uso de map para transformar os dados brutos em linhas formatadas antes de exibir
    const linhasFormatadas = produtos.map((item) => ({
        id: item.produto_id,
        nome: item.produto,
        categoria: item.categoria ?? 'Sem categoria',
        precoFormatado: formatarMoeda(Number(item.preco)),
        estoque: item.estoque,
        vendidos: item.quantidade_vendida,
    }));

    linhasFormatadas.forEach((linha) => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>#${linha.id}</td>
            <td><strong>${linha.nome}</strong></td>
            <td>${linha.categoria}</td>
            <td>${linha.precoFormatado}</td>
            <td>${linha.estoque} un.</td>
            <td>${linha.vendidos} un.</td>
        `;

        tbody.appendChild(tr);
    });
}

// ====================================================================
// FUNÇÃO: EXIBIR LISTA DE PRODUTOS COM ESTOQUE CRÍTICO
// ====================================================================
function exibirEstoqueCritico(produtos: Produto[]): void {
    const container = document.getElementById('lista-estoque-critico');
    if (!container) return;

    const criticos = filtrarEstoqueCritico(produtos);

    if (criticos.length === 0) {
        container.innerHTML = '<p>Nenhum produto com estoque crítico.</p>';
        return;
    }

    const itens = criticos
        .map((item) => `<li>${item.produto} — ${item.estoque} un. (${item.categoria ?? 'Sem categoria'})</li>`)
        .join('');

    container.innerHTML = `<ul>${itens}</ul>`;
}

// ====================================================================
// FUNÇÃO: TRATAMENTO DE CENÁRIO VAZIO (banco sem dados)
// ====================================================================
function exibirEstadoVazio(): void {
    const elFaturamento = document.getElementById('card-faturamento');
    if (elFaturamento) elFaturamento.innerText = 'Nenhum dado registrado';

    const elDestaque = document.getElementById('card-produto-destaque');
    if (elDestaque) elDestaque.innerText = 'Nenhum dado registrado';

    const elEstoqueCritico = document.getElementById('card-estoque-critico');
    if (elEstoqueCritico) elEstoqueCritico.innerText = '0';

    const tbody = document.getElementById('tabela-produtos-body');
    if (tbody) tbody.innerHTML = '<tr><td colspan="6">Nenhum dado registrado.</td></tr>';

    const listaCritico = document.getElementById('lista-estoque-critico');
    if (listaCritico) listaCritico.innerHTML = '<p>Nenhum dado registrado.</p>';
}

// ====================================================================
// FUNÇÃO: EXIBIR MENSAGEM DE ERRO (falha de rede ou banco)
// ====================================================================
function exibirErro(mensagem: string): void {
    const tbody = document.getElementById('tabela-produtos-body');
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="6">${mensagem}</td></tr>`;
    }
}

// ====================================================================
// FUNÇÃO AUXILIAR: FORMATAR NÚMERO PARA MOEDA (R$)
// ====================================================================
function formatarMoeda(valor: number): string {
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

// ====================================================================
// EVENTO: EXECUTA O CÓDIGO ASSIM QUE O HTML TERMINAR DE CARREGAR
// ====================================================================
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
