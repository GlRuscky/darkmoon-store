CREATE DATABASE IF NOT EXISTS loja_gotica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loja_gotica;

DROP TABLE IF EXISTS itens_pedido;
DROP TABLE IF EXISTS produto_categoria;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id_usuario   INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(150) NOT NULL,
    email        VARCHAR(150) NOT NULL UNIQUE,
    senha        VARCHAR(255) NOT NULL,
    endereco     VARCHAR(255),
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
    id_pedido       INT,
    id_produto      INT,
    quantidade      INT NOT NULL DEFAULT 1,
    preco_unitario  DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_pedido, id_produto),
    FOREIGN KEY (id_pedido)  REFERENCES pedidos(id_pedido)  ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE
);

CREATE TABLE produto_categoria (
    id_produto   INT,
    id_categoria INT,
    PRIMARY KEY (id_produto, id_categoria),
    FOREIGN KEY (id_produto)   REFERENCES produtos(id_produto)     ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE CASCADE
);

-- ===================== CATEGORIAS =====================
INSERT INTO categorias (nome) VALUES
('Camisas'),
('Vestidos'),
('Botas'),
('Acessórios');

-- ===================== PRODUTOS =====================
INSERT INTO produtos (nome, descricao, preco, estoque, imagem, genero) VALUES
('Camisa Raven Wings',      'Camisa feminina preta com estampa de cruz alada gótica em prata',                              89.90,  15, 'camisa-feminina1.webp',  'Feminino'),
('Camisa Kitty Thorn',      'Camisa oversized preta com Hello Kitty cercada por espinhos e design dark',                    94.90,  12, 'camisa-feminina2.webp',  'Feminino'),
('Camisa Tracker Angel',    'Camisa preta com estampa "TRACKER" guitarra e anjo com estrelas',                              89.90,  10, 'camisa-feminina3.webp',  'Feminino'),
('Camisa Attitude Flame',   'Camisa branca oversized com manga preta e logo "ATTITUDE" em estilo flamejante',               79.90,  18, 'camisa-feminina4.webp',  'Feminino'),
('Camisa Thorn Cross',      'Camisa preta justa com grande estampa de cruz espinhenta prateada',                            85.90,  14, 'camisa-feminina5.webp',  'Feminino'),
('Camisa Raven Skeleton',   'Camisa masculina preta com estampa de corvo esquelético vermelho sangrento',                   99.90,  12, 'camisa-masculina1.webp', 'Masculino'),
('Camisa Ghost Distressed', 'Camisa masculina preta destroyed com estampa "GHOST" em tie-dye vermelho e preto',            109.90,   8, 'camisa-masculina2.webp', 'Masculino'),
('Camisa Dark Layered',     'Camisa masculina preta com manga longa cinza e estampa gráfica gótica',                        95.90,  10, 'camisa-masculina3.webp', 'Masculino'),
('Camisa Angel Throne',     'Blusão cinza com estampa detalhada de anjo no trono e mangas com lettering',                 119.90,   9, 'camisa-masculina4.webp', 'Masculino'),
('Camisa Dark Anime',       'Camisa masculina preta washed com ilustração dark anime de figura mística',                    89.90,  15, 'camisa-masculina5.webp', 'Masculino'),

('Bota Feminina Midnight Bow',   'Bota feminina preta peluciada com laços',                              199.90, 10, 'bota-feminina1.webp',  'Feminino'),
('Bota Feminina Crimson Chain',  'Bota feminina preta e vermelha com correntes',                         219.90, 10, 'bota-feminina2.webp',  'Feminino'),
('Bota Feminina Dark Heel',      'Bota feminina preta com salto alto e detalhes dourados',               229.90, 10, 'bota-feminina3.webp',  'Feminino'),
('Bota Feminina Pink Star',      'Bota feminina rosa com plataforma e correntes de estrela',             239.90, 10, 'bota-feminina4.webp',  'Feminino'),
('Bota Feminina Heart Punk',     'Bota feminina preta de cano alto com fivelas de coração',              259.90, 10, 'bota-feminina5.webp',  'Feminino'),
('Bota Masculina Skull Strap',   'Bota masculina preta de cano alto com fivelas de caveira',             279.90, 10, 'bota-masculina1.webp', 'Masculino'),
('Bota Masculina Dark Rider',    'Bota masculina preta e vinho com cadarço e fivela',                    269.90, 10, 'bota-masculina2.webp', 'Masculino'),
('Bota Masculina Chain Combat',  'Bota masculina preta com correntes e solado tratorado',                249.90, 10, 'bota-masculina3.webp', 'Masculino'),
('Bota Masculina Buckle Combat', 'Bota masculina preta com múltiplas fivelas',                           249.90, 10, 'bota-masculina4.webp', 'Masculino'),
('Bota Masculina Glossy Night',  'Bota masculina preta envernizada com cadarço',                         259.90, 10, 'bota-masculina5.webp', 'Masculino'),

('Vestido Velvet Lace',        'Vestido curto preto de veludo com mangas longas de renda e decote quadrado',          159.90, 10, 'vestido1.webp', 'Feminino'),
('Vestido Off-Shoulder Gothic','Vestido preto tomara-que-caia de renda com saia assimétrica e luvas longas',          169.90,  8, 'vestido2.webp', 'Feminino'),
('Vestido White Siren',        'Vestido branco em renda com mangas flare, corset e saia irregular assimétrica',       149.90, 12, 'vestido3.webp', 'Feminino'),
('Vestido Corset Noir',        'Vestido preto curto em corset com laço, renda e detalhes de liga',                    179.90,  9, 'vestido4.webp', 'Feminino'),
('Vestido Crimson Velvet',     'Vestido vermelho em veludo com corset, camadas de renda preta e saia assimétrica',    189.90,  7, 'vestido5.webp', 'Feminino'),

('Colar Rosário Raven Cross', 'Colar longo estilo rosário gótico com cruz ornamentada e pingente de coração',  79.90, 15, 'acessorio1.webp', 'Unissex'),
('Colar Teia de Aranha',      'Colar statement em prata com design de teia de aranha e pingente de caveira',   89.90, 12, 'acessorio2.webp', 'Unissex'),
('Choker Spiked Punk',        'Choker preto de couro com spikes prateados grandes',                            59.90, 20, 'acessorio3.webp', 'Unissex'),
('Colar Layered Cross',       'Colar duplo em prata com cruz gótica detalhada e contas pretas',                69.90, 18, 'acessorio4.webp', 'Unissex'),
('Colar Rosário Blue Stone',  'Colar rosário gótico com pedra azul central, contas pretas e cruz ornamental',  85.90, 14, 'acessorio5.webp', 'Unissex'),
('Pulseira Spikes Classic',   'Pulseira de couro preta com spikes prateados',                                  49.90, 25, 'acessorio6.webp', 'Unissex'),
('Pulseira Skull Chain',      'Pulseira larga preta com spikes, caveiras, estrelas e correntes penduradas',    94.90, 10, 'acessorio7.webp', 'Unissex'),
('Pulseira Pyramid Studs',    'Pulseira de couro preta com pirâmides prateadas e rebites',                     64.90, 16, 'acessorio8.webp', 'Unissex');

-- ===================== VÍNCULOS PRODUTO x CATEGORIA =====================
INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria
FROM produtos p INNER JOIN categorias c ON c.id_categoria = 1
WHERE p.nome LIKE 'Camisa%';

INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria
FROM produtos p INNER JOIN categorias c ON c.id_categoria = 2
WHERE p.nome LIKE 'Vestido%';

INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria
FROM produtos p INNER JOIN categorias c ON c.id_categoria = 3
WHERE p.nome LIKE 'Bota%';

INSERT INTO produto_categoria (id_produto, id_categoria)
SELECT p.id_produto, c.id_categoria
FROM produtos p INNER JOIN categorias c ON c.id_categoria = 4
WHERE p.nome LIKE 'Colar%' OR p.nome LIKE 'Choker%' OR p.nome LIKE 'Pulseira%';
