-- ┌──────────────────────────────────────────────────────────────────┐
-- │ PARTE 1 — SCHEMA (estrutura das tabelas)                          │
-- └──────────────────────────────────────────────────────────────────┘

DROP DATABASE IF EXISTS loja_gotica;
CREATE DATABASE loja_gotica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loja_gotica;

CREATE TABLE usuarios (
    id_usuario    INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(150) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    senha         VARCHAR(255) NOT NULL,
    endereco      VARCHAR(255),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150) NOT NULL,
    descricao  TEXT,
    preco      DECIMAL(10,2) NOT NULL,
    estoque    INT NOT NULL,
    imagem     VARCHAR(255),
    genero     ENUM('Masculino','Feminino','Unissex') NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE pedidos (
    id_pedido   INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario  INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status      ENUM('Pendente','Pago','Enviado','Entregue','Cancelado') DEFAULT 'Pendente',
    total       DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE itens_pedido (
    id_pedido      INT,
    id_produto     INT,
    quantidade     INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_pedido, id_produto),
    FOREIGN KEY (id_pedido)  REFERENCES pedidos(id_pedido)   ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE produto_categoria (
    id_produto   INT,
    id_categoria INT,
    PRIMARY KEY (id_produto, id_categoria),
    FOREIGN KEY (id_produto)   REFERENCES produtos(id_produto)     ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE cupons (
    id_cupom            INT AUTO_INCREMENT PRIMARY KEY,
    codigo              VARCHAR(50) NOT NULL UNIQUE,
    percentual_desconto DECIMAL(5,2) NOT NULL,
    ativo               TINYINT(1) NOT NULL DEFAULT 1,
    data_criacao        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- ┌──────────────────────────────────────────────────────────────────┐
-- │ PARTE 2 — DADOS BASE (categorias, produtos, vínculos, cupons)     │
-- └──────────────────────────────────────────────────────────────────┘

INSERT INTO categorias (nome) VALUES
('Camisas'), ('Vestidos'), ('Botas'), ('Acessórios');

INSERT INTO produtos (nome, descricao, preco, estoque, imagem, genero) VALUES
-- Camisas
('Camisa Raven Wings',      'Camisa feminina preta com estampa de cruz alada gótica em prata',                    89.90,  15, 'camisa-feminina1.webp',  'Feminino'),
('Camisa Kitty Thorn',      'Camisa oversized preta com Hello Kitty cercada por espinhos e design dark',          94.90,  12, 'camisa-feminina2.webp',  'Feminino'),
('Camisa Tracker Angel',    'Camisa preta com estampa "TRACKER" guitarra e anjo com estrelas',                    89.90,  10, 'camisa-feminina3.webp',  'Feminino'),
('Camisa Attitude Flame',   'Camisa branca oversized com manga preta e logo "ATTITUDE" em estilo flamejante',     79.90,  18, 'camisa-feminina4.webp',  'Feminino'),
('Camisa Thorn Cross',      'Camisa preta justa com grande estampa de cruz espinhenta prateada',                  85.90,  14, 'camisa-feminina5.webp',  'Feminino'),
('Camisa Raven Skeleton',   'Camisa masculina preta com estampa de corvo esquelético vermelho sangrento',         99.90,  12, 'camisa-masculina1.webp', 'Masculino'),
('Camisa Ghost Distressed', 'Camisa masculina preta destroyed com estampa "GHOST" em tie-dye vermelho e preto',  109.90,   8, 'camisa-masculina2.webp', 'Masculino'),
('Camisa Dark Layered',     'Camisa masculina preta com manga longa cinza e estampa gráfica gótica',              95.90,  10, 'camisa-masculina3.webp', 'Masculino'),
('Camisa Angel Throne',     'Blusão cinza com estampa detalhada de anjo no trono e mangas com lettering',        119.90,   9, 'camisa-masculina4.webp', 'Masculino'),
('Camisa Dark Anime',       'Camisa masculina preta washed com ilustração dark anime de figura mística',          89.90,  15, 'camisa-masculina5.webp', 'Masculino'),

-- Botas
('Bota Feminina Midnight Bow',   'Bota feminina preta peluciada com laços',                     199.90, 10, 'bota-feminina1.webp',  'Feminino'),
('Bota Feminina Crimson Chain',  'Bota feminina preta e vermelha com correntes',                219.90, 10, 'bota-feminina2.webp',  'Feminino'),
('Bota Feminina Dark Heel',      'Bota feminina preta com salto alto e detalhes dourados',      229.90, 10, 'bota-feminina3.webp',  'Feminino'),
('Bota Feminina Pink Star',      'Bota feminina rosa com plataforma e correntes de estrela',    239.90, 10, 'bota-feminina4.webp',  'Feminino'),
('Bota Feminina Heart Punk',     'Bota feminina preta de cano alto com fivelas de coração',     259.90, 10, 'bota-feminina5.webp',  'Feminino'),
('Bota Masculina Skull Strap',   'Bota masculina preta de cano alto com fivelas de caveira',    279.90, 10, 'bota-masculina1.webp', 'Masculino'),
('Bota Masculina Dark Rider',    'Bota masculina preta e vinho com cadarço e fivela',           269.90, 10, 'bota-masculina2.webp', 'Masculino'),
('Bota Masculina Chain Combat',  'Bota masculina preta com correntes e solado tratorado',       249.90, 10, 'bota-masculina3.webp', 'Masculino'),
('Bota Masculina Buckle Combat', 'Bota masculina preta com múltiplas fivelas',                  249.90, 10, 'bota-masculina4.webp', 'Masculino'),
('Bota Masculina Glossy Night',  'Bota masculina preta envernizada com cadarço',                259.90, 10, 'bota-masculina5.webp', 'Masculino'),

-- Vestidos
('Vestido Velvet Lace',         'Vestido curto preto de veludo com mangas longas de renda e decote quadrado',        159.90, 10, 'vestido1.webp', 'Feminino'),
('Vestido Off-Shoulder Gothic', 'Vestido preto tomara-que-caia de renda com saia assimétrica e luvas longas',        169.90,  8, 'vestido2.webp', 'Feminino'),
('Vestido White Siren',         'Vestido branco em renda com mangas flare, corset e saia irregular assimétrica',     149.90, 12, 'vestido3.webp', 'Feminino'),
('Vestido Corset Noir',         'Vestido preto curto em corset com laço, renda e detalhes de liga',                  179.90,  9, 'vestido4.webp', 'Feminino'),
('Vestido Crimson Velvet',      'Vestido vermelho em veludo com corset, camadas de renda preta e saia assimétrica',  189.90,  7, 'vestido5.webp', 'Feminino'),

-- Acessórios
('Colar Rosário Raven Cross', 'Colar longo estilo rosário gótico com cruz ornamentada e pingente de coração', 79.90, 15, 'acessorio1.webp', 'Unissex'),
('Colar Teia de Aranha',      'Colar statement em prata com design de teia de aranha e pingente de caveira',  89.90, 12, 'acessorio2.webp', 'Unissex'),
('Choker Spiked Punk',        'Choker preto de couro com spikes prateados grandes',                           59.90, 20, 'acessorio3.webp', 'Unissex'),
('Colar Layered Cross',       'Colar duplo em prata com cruz gótica detalhada e contas pretas',               69.90, 18, 'acessorio4.webp', 'Unissex'),
('Colar Rosário Blue Stone',  'Colar rosário gótico com pedra azul central, contas pretas e cruz ornamental', 85.90, 14, 'acessorio5.webp', 'Unissex'),
('Pulseira Spikes Classic',   'Pulseira de couro preta com spikes prateados',                                 49.90, 25, 'acessorio6.webp', 'Unissex'),
('Pulseira Skull Chain',      'Pulseira larga preta com spikes, caveiras, estrelas e correntes penduradas',   94.90, 10, 'acessorio7.webp', 'Unissex'),
('Pulseira Pyramid Studs',    'Pulseira de couro preta com pirâmides prateadas e rebites',                    64.90, 16, 'acessorio8.webp', 'Unissex');

-- Vínculos produto x categoria (por padrão de nome)
INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria FROM produtos p, categorias c
WHERE c.id_categoria = 1 AND p.nome LIKE 'Camisa%';

INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria FROM produtos p, categorias c
WHERE c.id_categoria = 2 AND p.nome LIKE 'Vestido%';

INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria FROM produtos p, categorias c
WHERE c.id_categoria = 3 AND p.nome LIKE 'Bota%';

INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria FROM produtos p, categorias c
WHERE c.id_categoria = 4 AND (p.nome LIKE 'Colar%' OR p.nome LIKE 'Choker%' OR p.nome LIKE 'Pulseira%');

-- Cupons de desconto (usados em promocoes.php)
INSERT INTO cupons (codigo, percentual_desconto) VALUES
('DARK10',      10),
('MOON15',      15),
('GOTICA20',    20),
('BLACKFRIDAY', 30);


-- ┌──────────────────────────────────────────────────────────────────┐
-- │ PARTE 3 — DADOS DE TESTE (usuário + pedidos, para a dashboard)    │
-- └──────────────────────────────────────────────────────────────────┘

-- Senha: "123456" (hash bcrypt, compatível com password_hash() do PHP)
INSERT INTO usuarios (nome, email, senha, endereco) VALUES
('Cliente Teste', 'cliente.teste@darkmoon.com', '$2y$10$YbG6PKZa0EUEvE1zRKKz8.QhO9wRIhi3z8g8QG8kAoxg0oI8g9ULS', 'Rua das Sombras, 666');

INSERT INTO pedidos (id_usuario, status, total) VALUES
(1, 'Entregue', 284.70),
(1, 'Pago',     199.90),
(1, 'Enviado',  159.80),
(1, 'Pago',      89.90),
(1, 'Pago',      89.90);

-- Pedido 1: Camisa Raven Wings + Choker Spiked Punk + Colar Rosário Raven Cross
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 1, id_produto, 1, preco FROM produtos WHERE nome = 'Camisa Raven Wings';
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 1, id_produto, 2, preco FROM produtos WHERE nome = 'Choker Spiked Punk';
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 1, id_produto, 1, preco FROM produtos WHERE nome = 'Colar Rosário Raven Cross';

-- Pedido 2: Bota Feminina Midnight Bow
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 2, id_produto, 1, preco FROM produtos WHERE nome = 'Bota Feminina Midnight Bow';

-- Pedido 3: Camisa Ghost Distressed + Camisa Dark Anime
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 3, id_produto, 1, preco FROM produtos WHERE nome = 'Camisa Ghost Distressed';
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 3, id_produto, 1, preco FROM produtos WHERE nome = 'Camisa Dark Anime';

-- Pedidos 4 e 5: mais vendas de Camisa Raven Wings (pra destacar no ranking)
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 4, id_produto, 1, preco FROM produtos WHERE nome = 'Camisa Raven Wings';
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
SELECT 5, id_produto, 1, preco FROM produtos WHERE nome = 'Camisa Raven Wings';

-- Ajusta o estoque pra refletir as vendas simuladas acima
UPDATE produtos SET estoque = estoque - 4 WHERE nome = 'Camisa Raven Wings';
UPDATE produtos SET estoque = estoque - 2 WHERE nome = 'Choker Spiked Punk';
UPDATE produtos SET estoque = estoque - 1 WHERE nome = 'Colar Rosário Raven Cross';
UPDATE produtos SET estoque = estoque - 1 WHERE nome = 'Bota Feminina Midnight Bow';
UPDATE produtos SET estoque = estoque - 1 WHERE nome = 'Camisa Ghost Distressed';
UPDATE produtos SET estoque = estoque - 1 WHERE nome = 'Camisa Dark Anime';


-- ┌──────────────────────────────────────────────────────────────────┐
-- │ PARTE 4 — BANCO DE DADOS AVANÇADO                                 │
-- │ (View, Função, Trigger, Stored Procedure e CTE)                   │
-- └──────────────────────────────────────────────────────────────────┘

-- 4.1) VIEW: consolida produtos + categoria + vendas num só lugar
DROP VIEW IF EXISTS vw_produtos_completo;

CREATE VIEW vw_produtos_completo AS
SELECT
    p.id_produto,
    p.nome,
    p.descricao,
    p.preco,
    p.estoque,
    p.genero,
    c.nome AS categoria,
    COALESCE(SUM(ip.quantidade), 0)                    AS quantidade_vendida,
    COALESCE(SUM(ip.quantidade * ip.preco_unitario), 0) AS faturamento_gerado
FROM produtos p
LEFT JOIN produto_categoria pc ON pc.id_produto = p.id_produto
LEFT JOIN categorias c        ON c.id_categoria = pc.id_categoria
LEFT JOIN itens_pedido ip     ON ip.id_produto = p.id_produto
GROUP BY p.id_produto, p.nome, p.descricao, p.preco, p.estoque, p.genero, c.nome;
-- Teste: SELECT * FROM vw_produtos_completo;


-- 4.2) FUNÇÃO: calcula preço com desconto (reutilizável em qualquer consulta)
DROP FUNCTION IF EXISTS fn_preco_com_desconto;

DELIMITER $$
CREATE FUNCTION fn_preco_com_desconto(preco_original DECIMAL(10,2), percentual_desconto DECIMAL(5,2))
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    RETURN preco_original - (preco_original * (percentual_desconto / 100));
END$$
DELIMITER ;
-- Teste: SELECT nome, preco, fn_preco_com_desconto(preco, 15) FROM produtos LIMIT 5;


-- 4.3) TRIGGER: impede preço/estoque negativos em qualquer UPDATE
DROP TRIGGER IF EXISTS trg_produtos_valores_positivos;

DELIMITER $$
CREATE TRIGGER trg_produtos_valores_positivos
BEFORE UPDATE ON produtos
FOR EACH ROW
BEGIN
    IF NEW.preco < 0 THEN
        SET NEW.preco = 0;
    END IF;
    IF NEW.estoque < 0 THEN
        SET NEW.estoque = 0;
    END IF;
END$$
DELIMITER ;
-- Teste: UPDATE produtos SET estoque = -10 WHERE id_produto = 1;
--        SELECT estoque FROM produtos WHERE id_produto = 1;  -- deve mostrar 0


-- 4.4) STORED PROCEDURE: busca + filtro + paginação de produtos
DROP PROCEDURE IF EXISTS sp_listar_produtos;

DELIMITER $$
CREATE PROCEDURE sp_listar_produtos(
    IN p_categoria  VARCHAR(100),
    IN p_preco_min  DECIMAL(10,2),
    IN p_preco_max  DECIMAL(10,2),
    IN p_pagina     INT,
    IN p_por_pagina INT
)
BEGIN
    DECLARE v_offset INT;
    SET v_offset = (p_pagina - 1) * p_por_pagina;

    SELECT p.id_produto, p.nome, p.preco, p.estoque, c.nome AS categoria
    FROM produtos p
    LEFT JOIN produto_categoria pc ON pc.id_produto = p.id_produto
    LEFT JOIN categorias c        ON c.id_categoria = pc.id_categoria
    WHERE (p_categoria IS NULL OR c.nome = p_categoria)
      AND (p_preco_min IS NULL OR p.preco >= p_preco_min)
      AND (p_preco_max IS NULL OR p.preco <= p_preco_max)
    ORDER BY p.nome
    LIMIT p_por_pagina OFFSET v_offset;
END$$
DELIMITER ;
-- Teste: CALL sp_listar_produtos('Camisas', 50, 150, 1, 5);
--        CALL sp_listar_produtos(NULL, NULL, NULL, 1, 10);


-- 4.5) CTE: produto mais vendido dentro de cada categoria
WITH vendas_por_categoria AS (
    SELECT
        p.id_produto,
        p.nome,
        c.nome AS categoria,
        COALESCE(SUM(ip.quantidade), 0) AS total_vendido,
        ROW_NUMBER() OVER (
            PARTITION BY c.nome
            ORDER BY COALESCE(SUM(ip.quantidade), 0) DESC
        ) AS posicao_no_ranking
    FROM produtos p
    LEFT JOIN produto_categoria pc ON pc.id_produto = p.id_produto
    LEFT JOIN categorias c        ON c.id_categoria = pc.id_categoria
    LEFT JOIN itens_pedido ip     ON ip.id_produto = p.id_produto
    GROUP BY p.id_produto, p.nome, c.nome
)
SELECT * FROM vendas_por_categoria WHERE posicao_no_ranking = 1;