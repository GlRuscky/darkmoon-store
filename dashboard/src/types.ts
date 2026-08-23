/**
 * ====================================================================
 * DEFINIÇÃO DE TIPOS (TypeScript)
 * ====================================================================
 * O TypeScript nos permite definir a estrutura ("contrato") dos dados
 * que a API PHP retorna. Isso traz segurança ao código e ajuda o editor
 * a autocompletar os campos.
 */

// Type que representa o formato de um Produto vindo do banco de dados/API PHP
type Produto = {
    produto_id: number;
    produto: string;
    preco: number;
    estoque: number;
    genero: string;
    categoria: string;
    quantidade_vendida: number;
};
