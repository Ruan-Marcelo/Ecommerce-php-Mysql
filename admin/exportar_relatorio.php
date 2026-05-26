<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

global $pdo;
criar_tabelas_interacao_se_necessario();
criar_tabelas_email_se_necessario();
garantir_colunas_pagamento_pedidos();

$tipo = $_GET['tipo'] ?? 'pedidos';
$dias = isset($_GET['dias']) ? max(7, min(365, (int) $_GET['dias'])) : 30;

function exportar_csv($nome, $cabecalho, $linhas) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nome . '.csv"');
    echo "\xEF\xBB\xBF";
    $saida = fopen('php://output', 'w');
    fputcsv($saida, $cabecalho, ';');
    foreach ($linhas as $linha) {
        fputcsv($saida, $linha, ';');
    }
    fclose($saida);
    exit;
}

switch ($tipo) {
    case 'produtos':
        $stmt = $pdo->query("
            SELECT p.id, p.nome, c.nome AS categoria, p.preco, p.estoque, p.data_criacao,
                   COALESCE(SUM(i.quantidade), 0) AS vendidos,
                   COALESCE(SUM(i.quantidade * i.preco_unitario), 0) AS receita
            FROM produtos p
            LEFT JOIN categorias c ON c.id = p.categoria_id
            LEFT JOIN itens_pedido i ON i.produto_id = p.id
            GROUP BY p.id
            ORDER BY p.nome
        ");
        $linhas = [];
        foreach ($stmt->fetchAll() as $p) {
            $linhas[] = [$p['id'], $p['nome'], $p['categoria'], $p['preco'], $p['estoque'], $p['vendidos'], $p['receita'], $p['data_criacao']];
        }
        exportar_csv('lupiere_produtos', ['ID', 'Produto', 'Categoria', 'Preco', 'Estoque', 'Vendidos', 'Receita', 'Criado em'], $linhas);
        break;

    case 'clientes':
        $stmt = $pdo->query("
            SELECT u.id, u.nome, u.email, u.telefone, u.data_criacao,
                   COUNT(p.id) AS pedidos,
                   COALESCE(SUM(p.total), 0) AS total_gasto,
                   MAX(p.data_pedido) AS ultima_compra
            FROM usuarios u
            LEFT JOIN pedidos p ON p.usuario_id = u.id
            WHERE u.admin = 0
            GROUP BY u.id
            ORDER BY total_gasto DESC, u.nome
        ");
        $linhas = [];
        foreach ($stmt->fetchAll() as $u) {
            $linhas[] = [$u['id'], $u['nome'], $u['email'], $u['telefone'], $u['pedidos'], $u['total_gasto'], $u['ultima_compra'], $u['data_criacao']];
        }
        exportar_csv('lupiere_clientes', ['ID', 'Nome', 'Email', 'Telefone', 'Pedidos', 'Total gasto', 'Ultima compra', 'Cadastro'], $linhas);
        break;

    case 'estoque':
        $stmt = $pdo->query("
            SELECT p.id, p.nome, c.nome AS categoria, p.preco, p.estoque,
                   CASE WHEN p.estoque <= 2 THEN 'Critico' WHEN p.estoque <= 5 THEN 'Baixo' ELSE 'OK' END AS status_estoque
            FROM produtos p
            LEFT JOIN categorias c ON c.id = p.categoria_id
            ORDER BY p.estoque ASC, p.nome
        ");
        $linhas = [];
        foreach ($stmt->fetchAll() as $p) {
            $linhas[] = [$p['id'], $p['nome'], $p['categoria'], $p['preco'], $p['estoque'], $p['status_estoque']];
        }
        exportar_csv('lupiere_estoque', ['ID', 'Produto', 'Categoria', 'Preco', 'Estoque', 'Status'], $linhas);
        break;

    case 'emails':
        $stmt = $pdo->query("
            SELECT email, nome, origem, ativo, data_criacao, data_atualizacao
            FROM email_inscritos
            ORDER BY data_criacao DESC
        ");
        $linhas = [];
        foreach ($stmt->fetchAll() as $e) {
            $linhas[] = [$e['email'], $e['nome'], $e['origem'], $e['ativo'] ? 'Ativo' : 'Inativo', $e['data_criacao'], $e['data_atualizacao']];
        }
        exportar_csv('lupiere_emails', ['Email', 'Nome', 'Origem', 'Status', 'Criado em', 'Atualizado em'], $linhas);
        break;

    case 'carrinhos':
        $stmt = $pdo->query("
            SELECT c.id, u.nome, u.email, c.total, c.ativo, c.ultimo_email_em, c.data_atualizacao
            FROM carrinhos_abandonados c
            INNER JOIN usuarios u ON u.id = c.usuario_id
            ORDER BY c.data_atualizacao DESC
        ");
        $linhas = [];
        foreach ($stmt->fetchAll() as $c) {
            $linhas[] = [$c['id'], $c['nome'], $c['email'], $c['total'], $c['ativo'] ? 'Ativo' : 'Finalizado/inativo', $c['ultimo_email_em'], $c['data_atualizacao']];
        }
        exportar_csv('lupiere_carrinhos_abandonados', ['ID', 'Cliente', 'Email', 'Total', 'Status', 'Ultimo email', 'Atualizado em'], $linhas);
        break;

    case 'financeiro':
        $stmt = $pdo->prepare("
            SELECT DATE(data_pedido) AS dia, COUNT(*) AS pedidos, COALESCE(SUM(total), 0) AS receita, COALESCE(AVG(total), 0) AS ticket_medio
            FROM pedidos
            WHERE data_pedido >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(data_pedido)
            ORDER BY dia
        ");
        $stmt->execute([$dias]);
        $linhas = [];
        foreach ($stmt->fetchAll() as $f) {
            $linhas[] = [$f['dia'], $f['pedidos'], $f['receita'], $f['ticket_medio']];
        }
        exportar_csv('lupiere_financeiro_' . $dias . '_dias', ['Dia', 'Pedidos', 'Receita', 'Ticket medio'], $linhas);
        break;

    case 'pedidos':
    default:
        $stmt = $pdo->prepare("
            SELECT p.id, u.nome, u.email, p.total, p.status, p.forma_pagamento, p.status_pagamento, p.data_pedido
            FROM pedidos p
            LEFT JOIN usuarios u ON u.id = p.usuario_id
            WHERE p.data_pedido >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ORDER BY p.data_pedido DESC
        ");
        $stmt->execute([$dias]);
        $linhas = [];
        foreach ($stmt->fetchAll() as $p) {
            $linhas[] = [$p['id'], $p['nome'], $p['email'], $p['total'], $p['status'], $p['forma_pagamento'], $p['status_pagamento'], $p['data_pedido']];
        }
        exportar_csv('lupiere_pedidos_' . $dias . '_dias', ['Pedido', 'Cliente', 'Email', 'Total', 'Status', 'Pagamento', 'Status pagamento', 'Data'], $linhas);
        break;
}
