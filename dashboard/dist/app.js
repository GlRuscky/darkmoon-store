"use strict";

async function carregarDashboard() {
    try {

        const resposta = await fetch('api.php');

        if (!resposta.ok) {
            throw new Error(`Erro na requisição: Status ${resposta.status}`);
        }

        const produtos = await resposta.json();

        if (produtos.length === 0) {
            exibirEstadoVazio();
            return;
        }

        atualizarCards(produtos);
        exibirTabela(produtos);
        exibirEstoqueCritico(produtos);
    }
    catch (erro) {
        console.error('Falha ao carregar produtos:', erro);
        exibirErro('Não foi possível carregar os dados da API. Verifique a conexão com o banco.');
    }
}

function atualizarCards(produtos) {
    if (produtos.length === 0)
        return;

    const faturamentoTotal = produtos.reduce((acumulador, item) => {
        return acumulador + Number(item.preco) * Number(item.quantidade_vendida);
    }, 0);

    const produtoMaisVendido = encontrarProdutoMaisVendido(produtos);
    const estoqueCritico = filtrarEstoqueCritico(produtos);
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
function encontrarProdutoMaisVendido(produtos) {

    const contagem = {};
    produtos.forEach((item) => {
        contagem[item.produto_id] = Number(item.quantidade_vendida);
    });
    let idMaisVendido = null;
    let maiorQuantidade = 0;
    for (const idStr in contagem) {
        const id = Number(idStr);
        if (contagem[id] > maiorQuantidade) {
            maiorQuantidade = contagem[id];
            idMaisVendido = id;
        }
    }

    if (idMaisVendido === null) {
        return null;
    }
    return produtos.find((p) => p.produto_id === idMaisVendido) ?? null;
}

function filtrarEstoqueCritico(produtos, limite = 5) {
    return produtos.filter((item) => Number(item.estoque) < limite);
}

function exibirTabela(produtos) {
    const tbody = document.getElementById('tabela-produtos-body');
    if (!tbody)
        return;
    tbody.innerHTML = '';
    if (produtos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">Nenhum produto cadastrado.</td></tr>';
        return;
    }
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
function exibirEstoqueCritico(produtos) {
    const container = document.getElementById('lista-estoque-critico');
    if (!container)
        return;
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
function exibirEstadoVazio() {
    const elFaturamento = document.getElementById('card-faturamento');
    if (elFaturamento)
        elFaturamento.innerText = 'Nenhum dado registrado';
    const elDestaque = document.getElementById('card-produto-destaque');
    if (elDestaque)
        elDestaque.innerText = 'Nenhum dado registrado';
    const elEstoqueCritico = document.getElementById('card-estoque-critico');
    if (elEstoqueCritico)
        elEstoqueCritico.innerText = '0';
    const tbody = document.getElementById('tabela-produtos-body');
    if (tbody)
        tbody.innerHTML = '<tr><td colspan="6">Nenhum dado registrado.</td></tr>';
    const listaCritico = document.getElementById('lista-estoque-critico');
    if (listaCritico)
        listaCritico.innerHTML = '<p>Nenhum dado registrado.</p>';
}
function exibirErro(mensagem) {
    const tbody = document.getElementById('tabela-produtos-body');
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="6">${mensagem}</td></tr>`;
    }
}
function formatarMoeda(valor) {
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
