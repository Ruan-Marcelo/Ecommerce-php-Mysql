<?php
require_once __DIR__ . '/config.php';

function executar_consulta_paginada($sql, $params, $limite, $offset) {
    global $pdo;
    $stmt = $pdo->prepare($sql . " LIMIT ? OFFSET ?");
    foreach (array_values($params) as $index => $valor) {
        $stmt->bindValue($index + 1, $valor);
    }
    $stmt->bindValue(count($params) + 1, (int) $limite, PDO::PARAM_INT);
    $stmt->bindValue(count($params) + 2, (int) $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Função para obter todas as categorias
function obter_categorias() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
    return $stmt->fetchAll();
}

function obter_categorias_com_capa() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT
            c.*,
            COUNT(p.id) AS total_produtos,
            (
                SELECT p2.imagem
                FROM produtos p2
                WHERE p2.categoria_id = c.id AND p2.imagem IS NOT NULL AND p2.imagem <> ''
                ORDER BY p2.data_criacao DESC
                LIMIT 1
            ) AS imagem_capa
        FROM categorias c
        LEFT JOIN produtos p ON p.categoria_id = c.id
        GROUP BY c.id
        ORDER BY c.nome
    ");
    return $stmt->fetchAll();
}

// Função para obter categoria por ID
function obter_categoria_por_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function obter_categoria_por_nome($nome) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE LOWER(nome) = LOWER(?) LIMIT 1");
    $stmt->execute([$nome]);
    return $stmt->fetch();
}

function garantir_categoria_acessorios() {
    global $pdo;
    $categoria = obter_categoria_por_nome('Acessórios');
    if ($categoria) {
        return $categoria;
    }

    $categoria = obter_categoria_por_nome('Acessorios');
    if ($categoria) {
        return $categoria;
    }

    $stmt = $pdo->prepare("INSERT INTO categorias (nome, descricao, data_criacao) VALUES (?, ?, NOW())");
    $stmt->execute(['Acessórios', 'Acessórios de alfaiataria e complementos de estilo']);
    return obter_categoria_por_id($pdo->lastInsertId());
}

function criar_tabela_banners_se_necessario() {
    global $pdo;
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS banners (
            id int(11) NOT NULL AUTO_INCREMENT,
            titulo varchar(180) NOT NULL,
            subtitulo text,
            imagem varchar(255) NOT NULL,
            link_url varchar(255) DEFAULT 'produtos.php',
            texto_botao varchar(80) DEFAULT 'Explorar coleção',
            ativo tinyint(1) DEFAULT 1,
            ordem int(11) DEFAULT 0,
            data_criacao timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

function garantir_banner_padrao() {
    global $pdo;
    criar_tabela_banners_se_necessario();
    $total = (int) $pdo->query("SELECT COUNT(*) FROM banners")->fetchColumn();
    if ($total > 0) {
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO banners (titulo, subtitulo, imagem, link_url, texto_botao, ativo, ordem) VALUES (?, ?, ?, ?, ?, 1, 0)");
    $stmt->execute([
        'Elegância com Personalidade',
        'Peças feitas para quem impõe presença.',
        'public/assets/img/logo.jpg',
        'produtos.php',
        'Explorar coleção'
    ]);
}

function obter_banner_home() {
    global $pdo;
    criar_tabela_banners_se_necessario();
    $stmt = $pdo->query("SELECT * FROM banners WHERE ativo = 1 ORDER BY ordem ASC, id DESC LIMIT 1");
    return $stmt->fetch();
}

function obter_banners_admin() {
    global $pdo;
    criar_tabela_banners_se_necessario();
    $stmt = $pdo->query("SELECT * FROM banners ORDER BY ordem ASC, id DESC");
    return $stmt->fetchAll();
}

function adicionar_banner($titulo, $subtitulo, $imagem, $link_url, $texto_botao, $ativo, $ordem) {
    global $pdo;
    criar_tabela_banners_se_necessario();
    $stmt = $pdo->prepare("INSERT INTO banners (titulo, subtitulo, imagem, link_url, texto_botao, ativo, ordem) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$titulo, $subtitulo, $imagem, $link_url, $texto_botao, $ativo, $ordem]);
}

function atualizar_banner($id, $titulo, $subtitulo, $imagem, $link_url, $texto_botao, $ativo, $ordem) {
    global $pdo;
    criar_tabela_banners_se_necessario();
    if ($imagem !== '') {
        $stmt = $pdo->prepare("UPDATE banners SET titulo = ?, subtitulo = ?, imagem = ?, link_url = ?, texto_botao = ?, ativo = ?, ordem = ? WHERE id = ?");
        return $stmt->execute([$titulo, $subtitulo, $imagem, $link_url, $texto_botao, $ativo, $ordem, $id]);
    }

    $stmt = $pdo->prepare("UPDATE banners SET titulo = ?, subtitulo = ?, link_url = ?, texto_botao = ?, ativo = ?, ordem = ? WHERE id = ?");
    return $stmt->execute([$titulo, $subtitulo, $link_url, $texto_botao, $ativo, $ordem, $id]);
}

function excluir_banner($id) {
    global $pdo;
    criar_tabela_banners_se_necessario();
    $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
    return $stmt->execute([$id]);
}

function banner_imagem_url($imagem, $prefixo = '') {
    if (empty($imagem)) {
        return '';
    }

    if (filter_var($imagem, FILTER_VALIDATE_URL)) {
        return $imagem;
    }

    if (strpos($imagem, 'public/') === 0) {
        return $prefixo . $imagem;
    }

    return $prefixo . 'public/uploads/' . rawurlencode($imagem);
}

function criar_tabelas_interacao_se_necessario() {
    global $pdo;
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS comentarios_produto (
            id int(11) NOT NULL AUTO_INCREMENT,
            produto_id int(11) NOT NULL,
            usuario_id int(11) DEFAULT NULL,
            nome varchar(100) NOT NULL,
            comentario text NOT NULL,
            aprovado tinyint(1) DEFAULT 1,
            data_criacao timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id),
            KEY produto_id (produto_id),
            KEY usuario_id (usuario_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS avaliacoes_produto (
            id int(11) NOT NULL AUTO_INCREMENT,
            produto_id int(11) NOT NULL,
            usuario_id int(11) NOT NULL,
            nota tinyint(1) NOT NULL,
            data_criacao timestamp NOT NULL DEFAULT current_timestamp(),
            data_atualizacao timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY produto_usuario (produto_id, usuario_id),
            KEY produto_id (produto_id),
            KEY usuario_id (usuario_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lista_desejos (
            id int(11) NOT NULL AUTO_INCREMENT,
            usuario_id int(11) NOT NULL,
            produto_id int(11) NOT NULL,
            data_criacao timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id),
            UNIQUE KEY usuario_produto (usuario_id, produto_id),
            KEY usuario_id (usuario_id),
            KEY produto_id (produto_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

function obter_comentarios_produto($produto_id) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $stmt = $pdo->prepare("SELECT * FROM comentarios_produto WHERE produto_id = ? AND aprovado = 1 ORDER BY data_criacao DESC");
    $stmt->execute([$produto_id]);
    return $stmt->fetchAll();
}

function adicionar_comentario_produto($produto_id, $usuario_id, $nome, $comentario) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $stmt = $pdo->prepare("INSERT INTO comentarios_produto (produto_id, usuario_id, nome, comentario) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$produto_id, $usuario_id ?: null, $nome, $comentario]);
}

function salvar_avaliacao_produto($produto_id, $usuario_id, $nota) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $nota = max(1, min(5, (int) $nota));
    $stmt = $pdo->prepare("
        INSERT INTO avaliacoes_produto (produto_id, usuario_id, nota)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE nota = VALUES(nota), data_atualizacao = NOW()
    ");
    return $stmt->execute([$produto_id, $usuario_id, $nota]);
}

function obter_resumo_avaliacoes_produto($produto_id) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $stmt = $pdo->prepare("SELECT COALESCE(AVG(nota), 0) AS media, COUNT(*) AS total FROM avaliacoes_produto WHERE produto_id = ?");
    $stmt->execute([$produto_id]);
    $resumo = $stmt->fetch();
    return [
        'media' => round((float) ($resumo['media'] ?? 0), 1),
        'total' => (int) ($resumo['total'] ?? 0),
    ];
}

function obter_avaliacao_usuario_produto($produto_id, $usuario_id) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $stmt = $pdo->prepare("SELECT nota FROM avaliacoes_produto WHERE produto_id = ? AND usuario_id = ?");
    $stmt->execute([$produto_id, $usuario_id]);
    $avaliacao = $stmt->fetch();
    return $avaliacao ? (int) $avaliacao['nota'] : 0;
}

function renderizar_estrelas($media) {
    $media = (float) $media;
    $cheias = (int) round($media);
    $html = '<span class="inline-flex items-center gap-0.5 text-secondary" aria-label="Avaliação ' . escapar(number_format($media, 1, ',', '.')) . ' de 5">';
    for ($i = 1; $i <= 5; $i++) {
        $classe = $i <= $cheias ? 'text-secondary' : 'text-outline/40';
        $html .= '<span class="material-symbols-outlined text-[18px] ' . $classe . '">star</span>';
    }
    $html .= '</span>';
    return $html;
}

function produto_na_lista_desejos($usuario_id, $produto_id) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $stmt = $pdo->prepare("SELECT id FROM lista_desejos WHERE usuario_id = ? AND produto_id = ?");
    $stmt->execute([$usuario_id, $produto_id]);
    return $stmt->fetch() !== false;
}

function alternar_lista_desejos($usuario_id, $produto_id) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    if (produto_na_lista_desejos($usuario_id, $produto_id)) {
        $stmt = $pdo->prepare("DELETE FROM lista_desejos WHERE usuario_id = ? AND produto_id = ?");
        $stmt->execute([$usuario_id, $produto_id]);
        return false;
    }

    $stmt = $pdo->prepare("INSERT INTO lista_desejos (usuario_id, produto_id) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $produto_id]);
    return true;
}

function obter_lista_desejos_usuario($usuario_id) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome AS categoria_nome
        FROM lista_desejos l
        INNER JOIN produtos p ON p.id = l.produto_id
        LEFT JOIN categorias c ON c.id = p.categoria_id
        WHERE l.usuario_id = ?
        ORDER BY l.data_criacao DESC
    ");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll();
}

function obter_produtos_recomendados($limite = 4, $produto_id_excluir = null) {
    global $pdo;
    criar_tabelas_interacao_se_necessario();
    $sql = "
        SELECT p.*, c.nome AS categoria_nome, COALESCE(AVG(a.nota), 0) AS media_avaliacao
        FROM produtos p
        LEFT JOIN categorias c ON c.id = p.categoria_id
        LEFT JOIN avaliacoes_produto a ON a.produto_id = p.id
    ";
    $params = [];
    if ($produto_id_excluir) {
        $sql .= " WHERE p.id <> ? ";
        $params[] = $produto_id_excluir;
    }
    $sql .= " GROUP BY p.id ORDER BY media_avaliacao DESC, p.data_criacao DESC LIMIT ?";
    $stmt = $pdo->prepare($sql);
    foreach ($params as $index => $valor) {
        $stmt->bindValue($index + 1, $valor);
    }
    $stmt->bindValue(count($params) + 1, (int) $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Função para obter todos os produtos
function obter_produtos($limite = null, $offset = null) {
    global $pdo;
    $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id ORDER BY p.data_criacao DESC";
    if ($limite !== null && $offset !== null) {
        return executar_consulta_paginada($sql, [], $limite, $offset);
    } else {
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchAll();
}

// Função para obter produto por ID
function obter_produto_por_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.nome as categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Função para obter produtos por categoria
function obter_produtos_por_categoria($categoria_id, $limite = null, $offset = null) {
    global $pdo;
    $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.categoria_id = ? ORDER BY p.data_criacao DESC";
    if ($limite !== null && $offset !== null) {
        return executar_consulta_paginada($sql, [$categoria_id], $limite, $offset);
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$categoria_id]);
    }
    return $stmt->fetchAll();
}

// Função para buscar produtos por termo
function buscar_produtos($termo, $limite = null, $offset = null) {
    global $pdo;
    $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.nome LIKE ? OR p.descricao LIKE ? ORDER BY p.data_criacao DESC";
    $busca = "%$termo%";
    if ($limite !== null && $offset !== null) {
        return executar_consulta_paginada($sql, [$busca, $busca], $limite, $offset);
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$busca, $busca]);
    }
    return $stmt->fetchAll();
}

// Função para obter usuário por ID
function obter_usuario_por_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Função para obter usuário por email
function obter_usuario_por_email($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

// Função para criar novo usuário
function criar_usuario($nome, $email, $senha, $telefone = '', $admin = 0) {
    global $pdo;
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, telefone, admin, data_criacao) VALUES (?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([$nome, $email, $hash, $telefone, $admin]);
}

function senha_possui_hash($senha) {
    $info = password_get_info((string) $senha);
    return !empty($info['algo']);
}

function normalizar_hashes_senhas_usuarios() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, senha FROM usuarios");
    $usuarios = $stmt->fetchAll();
    $atualizados = 0;

    foreach ($usuarios as $usuario) {
        if (!senha_possui_hash($usuario['senha'])) {
            $hash = password_hash($usuario['senha'], PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $update->execute([$hash, $usuario['id']]);
            $atualizados++;
        }
    }

    return $atualizados;
}

function criar_ou_atualizar_admin_padrao($email = 'admin@lupiere.com', $senha = 'Admin@123') {
    global $pdo;
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $usuario = obter_usuario_por_email($email);

    if ($usuario) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, senha = ?, admin = 1 WHERE id = ?");
        return $stmt->execute(['Administrador Lupiere', $hash, $usuario['id']]);
    }

    return criar_usuario('Administrador Lupiere', $email, $senha, '', 1);
}

// Função para atualizar usuário
function atualizar_usuario($id, $nome, $email, $telefone, $senha = null) {
    global $pdo;
    if ($senha) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id = ?");
        return $stmt->execute([$nome, $email, $telefone, $hash, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?");
        return $stmt->execute([$nome, $email, $telefone, $id]);
    }
}

// Função para verificar se email já existe
function email_existe($email, $except_id = null) {
    global $pdo;
    if ($except_id) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $except_id]);
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
    }
    return $stmt->fetch() !== false;
}

// Função para obter contagem total de produtos
function contar_produtos() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para obter contagem total de produtos por categoria
function contar_produtos_por_categoria($categoria_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?");
    $stmt->execute([$categoria_id]);
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para obter contagem total de produtos na busca
function contar_produtos_busca($termo) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE nome LIKE ? OR descricao LIKE ?");
    $busca = "%$termo%";
    $stmt->execute([$busca, $busca]);
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para adicionar produto (admin)
function adicionar_produto($nome, $descricao, $preco, $estoque, $categoria_id, $imagem = null) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id, imagem, data_criacao) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id, $imagem]);
}

// Função para atualizar produto (admin)
function atualizar_produto($id, $nome, $descricao, $preco, $estoque, $categoria_id, $imagem = null) {
    global $pdo;
    if ($imagem) {
        $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ?, imagem = ? WHERE id = ?");
        return $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id, $imagem, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ? WHERE id = ?");
        return $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id, $id]);
    }
}

// Função para excluir produto (admin)
function excluir_produto($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    return $stmt->execute([$id]);
}

// Função para obter todos os pedidos (admin)
function obter_pedidos($limite = null, $offset = null) {
    global $pdo;
    $sql = "SELECT p.*, u.nome as usuario_nome FROM pedidos p LEFT JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.data_pedido DESC";
    if ($limite !== null && $offset !== null) {
        return executar_consulta_paginada($sql, [], $limite, $offset);
    } else {
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchAll();
}

// Função para obter pedido por ID com itens
function obter_pedido_por_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, u.nome as usuario_nome, u.email as usuario_email FROM pedidos p LEFT JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch();
    if ($pedido) {
        $itens = obter_itens_pedido($id);
        $pedido['itens'] = $itens;
    }
    return $pedido;
}

// Função para obter itens de um pedido
function obter_itens_pedido($pedido_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT i.*, p.nome as produto_nome, p.imagem as produto_imagem FROM itens_pedido i LEFT JOIN produtos p ON i.produto_id = p.id WHERE i.pedido_id = ?");
    $stmt->execute([$pedido_id]);
    return $stmt->fetchAll();
}

// Função para obter pedidos do usuário
function obter_pedidos_usuario($usuario_id, $limite = null, $offset = null) {
    global $pdo;
    $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC";
    if ($limite !== null && $offset !== null) {
        return executar_consulta_paginada($sql, [$usuario_id], $limite, $offset);
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id]);
    }
    return $stmt->fetchAll();
}

// Função para contar pedidos do usuário
function contar_pedidos_usuario($usuario_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pedidos WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para contar categorias
function contar_categorias() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM categorias");
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para contar usuários
function contar_usuarios() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para contar pedidos (admin)
function contar_pedidos() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pedidos");
    $result = $stmt->fetch();
    return $result['total'];
}

// Função para finalizar compra (criar pedido e itens)
function finalizar_compra($usuario_id, $carrinho, $total, $endereco_entrega) {
    global $pdo;
    $pdo->beginTransaction();
    try {
        // Inserir pedido
        $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, endereco_entrega, status, data_pedido) VALUES (?, ?, ?, 'pendente', NOW())");
        $stmt->execute([$usuario_id, $total, $endereco_entrega]);
        $pedido_id = $pdo->lastInsertId();

        // Inserir itens do pedido
        foreach ($carrinho as $item) {
            $produto_id = $item['produto_id'] ?? $item['id'] ?? null;
            if (!$produto_id) {
                throw new InvalidArgumentException('Item do carrinho sem produto_id');
            }

            $stmt_item = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            $stmt_item->execute([$pedido_id, $produto_id, $item['quantidade'], $item['preco']]);

            // Atualizar estoque
            $stmt_estoque = $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
            $stmt_estoque->execute([$item['quantidade'], $produto_id]);
        }

        $pdo->commit();
        return $pedido_id;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erro ao finalizar compra: " . $e->getMessage());
        return false;
    }
}

// Função para limpar carrinho da sessão
function limpar_carrinho() {
    unset($_SESSION['carrinho']);
}

// Função para obter total do carrinho
function obter_total_carrinho() {
    $total = 0;
    if (!empty($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
    }
    return $total;
}

// Função para obter quantidade total de itens no carrinho
function obter_quantidade_carrinho() {
    $quantidade = 0;
    if (!empty($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $quantidade += $item['quantidade'];
        }
    }
    return $quantidade;
}

// Função para fazer upload de imagem
function upload_imagem($file, $pasta = null) {
    $pasta = $pasta ?: dirname(__DIR__, 2) . '/public/uploads';

    // Verificar se há erro no upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['erro' => 'Erro no upload do arquivo'];
    }

    // Verificar tipo de arquivo
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $tipos_permitidos)) {
        return ['erro' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP'];
    }

    // Verificar tamanho (5MB máximo)
    $tamanho_maximo = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $tamanho_maximo) {
        return ['erro' => 'Arquivo muito grande. Tamanho máximo: 5MB'];
    }

    // Gerar nome único para o arquivo
    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $nome_arquivo = uniqid('img_', true) . '.' . $extensao;
    $caminho_destino = rtrim($pasta, '/\\') . '/' . $nome_arquivo;

    // Criar pasta se não existir
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $caminho_destino)) {
        return ['sucesso' => true, 'nome' => $nome_arquivo, 'caminho' => $caminho_destino];
    } else {
        return ['erro' => 'Erro ao mover o arquivo'];
    }
}

// Função para verificar se usuário é admin
function imagem_produto_url($imagem, $prefixo = '') {
    if (empty($imagem)) {
        return '';
    }

    if (filter_var($imagem, FILTER_VALIDATE_URL)) {
        return $imagem;
    }

    return $prefixo . 'public/uploads/' . rawurlencode($imagem);
}

function imagem_produto_disponivel($imagem, $diretorio_uploads = null) {
    if (empty($imagem)) {
        return false;
    }

    if (filter_var($imagem, FILTER_VALIDATE_URL)) {
        return true;
    }

    $diretorio_uploads = $diretorio_uploads ?: dirname(__DIR__, 2) . '/public/uploads';
    return file_exists($diretorio_uploads . '/' . $imagem);
}

function eh_admin($usuario_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT admin FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();
    return $usuario && $usuario['admin'] == 1;
}

// Função para proteger página (redireciona se não logado)
function proteger_pagina() {
    if (!isset($_SESSION["usuario_id"])) {
        header("Location: login.php");
        exit();
    }
}

// Função para proteger página admin (redireciona se não logado ou não admin)
function proteger_pagina_admin() {
    if (!isset($_SESSION["usuario_id"])) {
        header("Location: ../login.php");
        exit();
    }
    if (!eh_admin($_SESSION["usuario_id"])) {
        header("Location: ../index.php");
        exit();
    }
}

// Função para exibir mensagem de sucesso
function mensagem_sucesso($texto) {
    return '<div class="mb-4 p-4 bg-green-500/20 text-green-600 rounded-lg">' . $texto . '</div>';
}

// Função para exibir mensagem de erro
function mensagem_erro($texto) {
    return '<div class="mb-4 p-4 bg-red-500/20 text-red-600 rounded-lg">' . $texto . '</div>';
}

// Função para sanitizar saída (escape HTML)
function escapar($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

// Função para formatar moeda
function formatar_moeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Função para gerar slug (para URLs amigáveis)
function gerar_slug($texto) {
    $slug = strtolower(trim($texto));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return $slug;
}
?>
