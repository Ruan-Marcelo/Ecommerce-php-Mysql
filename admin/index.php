<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

global $pdo;
criar_tabelas_interacao_se_necessario();
criar_tabelas_email_se_necessario();
garantir_colunas_pagamento_pedidos();

$titulo_pagina = 'Dashboard';
$usuario = obter_usuario_por_id($_SESSION['usuario_id']);
$dias = isset($_GET['dias']) ? max(7, min(365, (int) $_GET['dias'])) : 30;
$periodos = [7 => '7 dias', 30 => '30 dias', 90 => '90 dias', 365 => '12 meses'];

function numero_dashboard($valor) {
    return number_format((float) $valor, 0, ',', '.');
}

function porcentagem_dashboard($valor) {
    return number_format((float) $valor, 1, ',', '.') . '%';
}

function status_pedido_label($status) {
    $labels = [
        'pendente' => 'Pendente',
        'processando' => 'Processando',
        'enviado' => 'Enviado',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado',
    ];
    return $labels[$status] ?? $status;
}

$stmt = $pdo->prepare("
    SELECT
        COUNT(*) AS pedidos,
        COALESCE(SUM(total), 0) AS receita,
        COALESCE(AVG(total), 0) AS ticket_medio
    FROM pedidos
    WHERE data_pedido >= DATE_SUB(NOW(), INTERVAL ? DAY)
");
$stmt->execute([$dias]);
$resumo_periodo = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT usuario_id)
    FROM pedidos
    WHERE data_pedido >= DATE_SUB(NOW(), INTERVAL ? DAY)
");
$stmt->execute([$dias]);
$clientes_compradores = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM usuarios
    WHERE admin = 0 AND data_criacao >= DATE_SUB(NOW(), INTERVAL ? DAY)
");
$stmt->execute([$dias]);
$novos_clientes = (int) $stmt->fetchColumn();

$total_produtos = (int) contar_produtos();
$total_categorias = (int) contar_categorias();
$total_usuarios = (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE admin = 0")->fetchColumn();
$total_pedidos = (int) contar_pedidos();
$total_inscritos = (int) $pdo->query("SELECT COUNT(*) FROM email_inscritos WHERE ativo = 1")->fetchColumn();
$emails_pendentes = (int) $pdo->query("SELECT COUNT(*) FROM email_fila WHERE status = 'pendente'")->fetchColumn();
$carrinhos_abandonados = (int) $pdo->query("SELECT COUNT(*) FROM carrinhos_abandonados WHERE ativo = 1")->fetchColumn();
$itens_desejados = (int) $pdo->query("SELECT COUNT(*) FROM lista_desejos")->fetchColumn();
$produtos_baixo_estoque = (int) $pdo->query("SELECT COUNT(*) FROM produtos WHERE estoque <= 5")->fetchColumn();

$taxa_conversao = $total_usuarios > 0 ? ($clientes_compradores / $total_usuarios) * 100 : 0;

$stmt = $pdo->prepare("
    SELECT DATE(data_pedido) AS dia, COUNT(*) AS pedidos, COALESCE(SUM(total), 0) AS receita
    FROM pedidos
    WHERE data_pedido >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
    GROUP BY DATE(data_pedido)
    ORDER BY dia
");
$stmt->execute([$dias]);
$vendas_por_dia = $stmt->fetchAll();

$stmt = $pdo->query("SELECT status, COUNT(*) AS total FROM pedidos GROUP BY status ORDER BY total DESC");
$pedidos_por_status = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT c.nome AS categoria, COALESCE(SUM(i.quantidade * i.preco_unitario), 0) AS receita
    FROM itens_pedido i
    INNER JOIN pedidos p ON p.id = i.pedido_id
    INNER JOIN produtos pr ON pr.id = i.produto_id
    LEFT JOIN categorias c ON c.id = pr.categoria_id
    WHERE p.data_pedido >= DATE_SUB(NOW(), INTERVAL ? DAY)
    GROUP BY c.id, c.nome
    ORDER BY receita DESC
    LIMIT 6
");
$stmt->execute([$dias]);
$receita_por_categoria = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT pr.id, pr.nome, pr.estoque, COALESCE(SUM(i.quantidade), 0) AS vendidos, COALESCE(SUM(i.quantidade * i.preco_unitario), 0) AS receita
    FROM produtos pr
    LEFT JOIN itens_pedido i ON i.produto_id = pr.id
    LEFT JOIN pedidos p ON p.id = i.pedido_id AND p.data_pedido >= DATE_SUB(NOW(), INTERVAL ? DAY)
    GROUP BY pr.id
    ORDER BY vendidos DESC, receita DESC
    LIMIT 8
");
$stmt->execute([$dias]);
$top_produtos = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT id, nome, estoque, preco
    FROM produtos
    WHERE estoque <= 5
    ORDER BY estoque ASC, nome ASC
    LIMIT 8
");
$estoque_critico = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT p.*, u.nome AS usuario_nome, u.email AS usuario_email
    FROM pedidos p
    LEFT JOIN usuarios u ON u.id = p.usuario_id
    ORDER BY p.data_pedido DESC
    LIMIT 8
");
$pedidos_recentes = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT u.nome, u.email, COUNT(p.id) AS pedidos, COALESCE(SUM(p.total), 0) AS total_gasto
    FROM usuarios u
    INNER JOIN pedidos p ON p.usuario_id = u.id
    WHERE u.admin = 0
    GROUP BY u.id
    ORDER BY total_gasto DESC
    LIMIT 6
");
$melhores_clientes = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT p.nome, COUNT(l.id) AS desejos
    FROM lista_desejos l
    INNER JOIN produtos p ON p.id = l.produto_id
    GROUP BY p.id
    ORDER BY desejos DESC
    LIMIT 6
");
$produtos_desejados = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT
        SUM(status = 'enviado') AS enviados,
        SUM(status = 'pendente') AS pendentes,
        SUM(status = 'falhou') AS falhas
    FROM email_fila
");
$email_stats = $stmt->fetch();

$chart_vendas_labels = array_map(fn($linha) => date('d/m', strtotime($linha['dia'])), $vendas_por_dia);
$chart_vendas_receita = array_map(fn($linha) => (float) $linha['receita'], $vendas_por_dia);
$chart_vendas_pedidos = array_map(fn($linha) => (int) $linha['pedidos'], $vendas_por_dia);
$chart_status_labels = array_map(fn($linha) => status_pedido_label($linha['status']), $pedidos_por_status);
$chart_status_data = array_map(fn($linha) => (int) $linha['total'], $pedidos_por_status);
$chart_categoria_labels = array_map(fn($linha) => $linha['categoria'] ?: 'Sem categoria', $receita_por_categoria);
$chart_categoria_data = array_map(fn($linha) => (float) $linha['receita'], $receita_por_categoria);

require_once dirname(__DIR__) . '/app/views/includes/head.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  .metric-card {
    min-height: 148px;
  }
  .chart-box {
    height: 320px;
  }
  .compact-table td,
  .compact-table th {
    white-space: nowrap;
  }
</style>

<aside class="fixed top-0 left-0 h-full w-64 bg-primary text-on-primary z-40 flex flex-col">
  <div class="flex items-center justify-center py-8">
    <div class="text-xl font-headline-lg tracking-[0.4em] text-white">LUPIERE ADMIN</div>
  </div>
  <nav class="flex-1 flex-col pt-6 space-y-4">
    <a href="index.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">dashboard</span><span class="ml-3">Dashboard</span></a>
    <a href="produtos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">inventory_2</span><span class="ml-3">Produtos</span></a>
    <a href="categorias.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">category</span><span class="ml-3">Categorias</span></a>
    <a href="pedidos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">list_alt</span><span class="ml-3">Pedidos</span></a>
    <a href="banners.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">image</span><span class="ml-3">Banners</span></a>
    <a href="emails.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">mail</span><span class="ml-3">E-mails</span></a>
    <a href="administradores.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">admin_panel_settings</span><span class="ml-3">Administradores</span></a>
    <a href="../logout.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors mt-auto"><span class="notranslate material-symbols-outlined" translate="no">logout</span><span class="ml-3">Sair</span></a>
  </nav>
</aside>

<main class="flex-grow ml-64 flex flex-col">
  <header class="fixed top-0 left-64 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-16 flex items-center">
    <div class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto">
      <div>
        <div class="text-xl md:text-2xl font-headline-lg tracking-[0.3em] text-[#1B3022]">Dashboard</div>
      </div>
      <div class="flex items-center gap-6 text-[#1B3022]">
        <form method="get" class="flex items-center gap-3">
          <span class="notranslate material-symbols-outlined text-[20px]" translate="no">calendar_month</span>
          <select name="dias" class="form-input-bespoke py-2 text-sm text-primary" onchange="this.form.submit()">
            <?php foreach ($periodos as $valor => $label): ?>
              <option value="<?php echo $valor; ?>" <?php echo $dias === $valor ? 'selected' : ''; ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
          </select>
        </form>
        <span class="notranslate material-symbols-outlined" translate="no">account_circle</span>
        <span class="text-on-surface-variant/70"><?php echo escapar($usuario['nome'] ?? 'Usuario'); ?></span>
      </div>
    </div>
  </header>

  <div class="flex-grow py-28 px-gutter">
    <div class="max-w-[1440px] mx-auto space-y-8">
      <section class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
        <div>
          <p class="font-label-caps text-label-caps text-secondary uppercase mb-3">Visao gerencial</p>
          <h1 class="font-headline-lg text-headline-lg text-primary">Controle completo da LUPIERE</h1>
          <p class="text-on-surface-variant mt-3 max-w-2xl">Acompanhe vendas, clientes, estoque, pedidos, desejos, carrinhos abandonados e desempenho dos e-mails em um unico lugar.</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <a href="exportar_relatorio.php?tipo=pedidos&dias=<?php echo $dias; ?>" class="inline-flex items-center gap-2 bg-primary-container text-white px-4 py-3 font-label-caps text-label-caps tracking-[0.16em] hover:bg-primary transition-all"><span class="notranslate material-symbols-outlined text-[18px]" translate="no">download</span>Pedidos</a>
          <a href="exportar_relatorio.php?tipo=produtos" class="inline-flex items-center gap-2 border border-outline/30 text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.16em] hover:bg-surface-container-low transition-all"><span class="notranslate material-symbols-outlined text-[18px]" translate="no">download</span>Produtos</a>
          <a href="exportar_relatorio.php?tipo=clientes" class="inline-flex items-center gap-2 border border-outline/30 text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.16em] hover:bg-surface-container-low transition-all"><span class="notranslate material-symbols-outlined text-[18px]" translate="no">download</span>Clientes</a>
          <a href="exportar_relatorio.php?tipo=estoque" class="inline-flex items-center gap-2 border border-outline/30 text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.16em] hover:bg-surface-container-low transition-all"><span class="notranslate material-symbols-outlined text-[18px]" translate="no">download</span>Estoque</a>
        </div>
      </section>

      <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="metric-card bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex justify-between gap-4">
            <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant/60">Receita no periodo</p>
              <p class="font-headline-md text-[34px] text-primary mt-3"><?php echo formatar_moeda($resumo_periodo['receita']); ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center bg-green-500/15 text-green-700 rounded-lg"><span class="notranslate material-symbols-outlined" translate="no">payments</span></div>
          </div>
          <p class="text-sm text-on-surface-variant mt-4"><?php echo numero_dashboard($resumo_periodo['pedidos']); ?> pedidos em <?php echo $dias; ?> dias</p>
        </div>
        <div class="metric-card bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex justify-between gap-4">
            <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant/60">Ticket medio</p>
              <p class="font-headline-md text-[34px] text-primary mt-3"><?php echo formatar_moeda($resumo_periodo['ticket_medio']); ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center bg-secondary/15 text-secondary rounded-lg"><span class="notranslate material-symbols-outlined" translate="no">monitoring</span></div>
          </div>
          <p class="text-sm text-on-surface-variant mt-4">Media por pedido confirmado no periodo</p>
        </div>
        <div class="metric-card bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex justify-between gap-4">
            <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant/60">Clientes ativos</p>
              <p class="font-headline-md text-[34px] text-primary mt-3"><?php echo numero_dashboard($clientes_compradores); ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center bg-blue-500/15 text-blue-700 rounded-lg"><span class="notranslate material-symbols-outlined" translate="no">groups</span></div>
          </div>
          <p class="text-sm text-on-surface-variant mt-4">Conversao da base: <?php echo porcentagem_dashboard($taxa_conversao); ?></p>
        </div>
        <div class="metric-card bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex justify-between gap-4">
            <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant/60">Alertas de estoque</p>
              <p class="font-headline-md text-[34px] text-primary mt-3"><?php echo numero_dashboard($produtos_baixo_estoque); ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center bg-red-500/15 text-red-700 rounded-lg"><span class="notranslate material-symbols-outlined" translate="no">warning</span></div>
          </div>
          <p class="text-sm text-on-surface-variant mt-4">Produtos com 5 unidades ou menos</p>
        </div>
      </section>

      <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-6">
        <div class="bg-surface rounded-lg border border-outline/20 p-5"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Produtos</p><p class="font-headline-md text-[30px] text-primary mt-2"><?php echo numero_dashboard($total_produtos); ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-5"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Categorias</p><p class="font-headline-md text-[30px] text-primary mt-2"><?php echo numero_dashboard($total_categorias); ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-5"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Clientes</p><p class="font-headline-md text-[30px] text-primary mt-2"><?php echo numero_dashboard($total_usuarios); ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-5"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Pedidos</p><p class="font-headline-md text-[30px] text-primary mt-2"><?php echo numero_dashboard($total_pedidos); ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-5"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Inscritos</p><p class="font-headline-md text-[30px] text-primary mt-2"><?php echo numero_dashboard($total_inscritos); ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-5"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Desejos</p><p class="font-headline-md text-[30px] text-primary mt-2"><?php echo numero_dashboard($itens_desejados); ?></p></div>
      </section>

      <section class="grid gap-8 xl:grid-cols-3">
        <div class="xl:col-span-2 bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex items-start justify-between gap-4 mb-6">
            <div>
              <h2 class="font-headline-md text-headline-md text-primary">Vendas por dia</h2>
              <p class="text-on-surface-variant">Receita e volume de pedidos no periodo selecionado.</p>
            </div>
            <span class="notranslate material-symbols-outlined text-secondary" translate="no">query_stats</span>
          </div>
          <div class="chart-box"><canvas id="vendasChart"></canvas></div>
        </div>
        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex items-start justify-between gap-4 mb-6">
            <div>
              <h2 class="font-headline-md text-headline-md text-primary">Status dos pedidos</h2>
              <p class="text-on-surface-variant">Distribuicao operacional.</p>
            </div>
            <span class="notranslate material-symbols-outlined text-secondary" translate="no">donut_large</span>
          </div>
          <div class="chart-box"><canvas id="statusChart"></canvas></div>
        </div>
      </section>

      <section class="grid gap-8 xl:grid-cols-3">
        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md text-primary mb-6">Receita por categoria</h2>
          <div class="chart-box"><canvas id="categoriaChart"></canvas></div>
        </div>
        <div class="xl:col-span-2 bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex items-start justify-between mb-6">
            <div>
              <h2 class="font-headline-md text-headline-md text-primary">Produtos mais vendidos</h2>
              <p class="text-on-surface-variant">Priorize reposicao e campanhas com base na saida.</p>
            </div>
            <a href="exportar_relatorio.php?tipo=produtos" class="text-sm text-secondary hover:underline">Exportar</a>
          </div>
          <div class="overflow-x-auto">
            <table class="compact-table min-w-full divide-y divide-outline/20">
              <thead class="bg-primary/10">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-label-caps text-label-caps text-on-surface-variant/60">Produto</th>
                  <th class="px-4 py-3 text-right text-xs font-label-caps text-label-caps text-on-surface-variant/60">Vendidos</th>
                  <th class="px-4 py-3 text-right text-xs font-label-caps text-label-caps text-on-surface-variant/60">Receita</th>
                  <th class="px-4 py-3 text-right text-xs font-label-caps text-label-caps text-on-surface-variant/60">Estoque</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-outline/10">
                <?php foreach ($top_produtos as $produto): ?>
                  <tr>
                    <td class="px-4 py-3 text-primary"><?php echo escapar($produto['nome']); ?></td>
                    <td class="px-4 py-3 text-right"><?php echo numero_dashboard($produto['vendidos']); ?></td>
                    <td class="px-4 py-3 text-right"><?php echo formatar_moeda($produto['receita']); ?></td>
                    <td class="px-4 py-3 text-right <?php echo (int) $produto['estoque'] <= 5 ? 'text-red-600' : 'text-on-surface'; ?>"><?php echo (int) $produto['estoque']; ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <section class="grid gap-8 xl:grid-cols-3">
        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md text-primary mb-6">Indicadores de marketing</h2>
          <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-outline/10 pb-4"><span>Inscritos ativos</span><strong><?php echo numero_dashboard($total_inscritos); ?></strong></div>
            <div class="flex items-center justify-between border-b border-outline/10 pb-4"><span>E-mails pendentes</span><strong><?php echo numero_dashboard($emails_pendentes); ?></strong></div>
            <div class="flex items-center justify-between border-b border-outline/10 pb-4"><span>E-mails enviados</span><strong><?php echo numero_dashboard($email_stats['enviados'] ?? 0); ?></strong></div>
            <div class="flex items-center justify-between border-b border-outline/10 pb-4"><span>Falhas de envio</span><strong class="<?php echo (int) ($email_stats['falhas'] ?? 0) > 0 ? 'text-red-600' : ''; ?>"><?php echo numero_dashboard($email_stats['falhas'] ?? 0); ?></strong></div>
            <div class="flex items-center justify-between border-b border-outline/10 pb-4"><span>Carrinhos abandonados</span><strong><?php echo numero_dashboard($carrinhos_abandonados); ?></strong></div>
            <div class="flex items-center justify-between"><span>Novos clientes</span><strong><?php echo numero_dashboard($novos_clientes); ?></strong></div>
          </div>
        </div>

        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md text-primary mb-6">Mais desejados</h2>
          <div class="space-y-4">
            <?php if (empty($produtos_desejados)): ?>
              <p class="text-on-surface-variant">Nenhum item em listas de desejos ainda.</p>
            <?php endif; ?>
            <?php foreach ($produtos_desejados as $produto): ?>
              <div class="flex items-center justify-between gap-4 border-b border-outline/10 pb-4">
                <span class="line-clamp-1"><?php echo escapar($produto['nome']); ?></span>
                <strong><?php echo numero_dashboard($produto['desejos']); ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md text-primary mb-6">Estoque critico</h2>
          <div class="space-y-4">
            <?php if (empty($estoque_critico)): ?>
              <p class="text-on-surface-variant">Nenhum produto em estoque critico.</p>
            <?php endif; ?>
            <?php foreach ($estoque_critico as $produto): ?>
              <div class="flex items-center justify-between gap-4 border-b border-outline/10 pb-4">
                <div>
                  <p class="line-clamp-1 text-primary"><?php echo escapar($produto['nome']); ?></p>
                  <p class="text-sm text-on-surface-variant"><?php echo formatar_moeda($produto['preco']); ?></p>
                </div>
                <strong class="<?php echo (int) $produto['estoque'] <= 2 ? 'text-red-600' : 'text-secondary'; ?>"><?php echo (int) $produto['estoque']; ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="grid gap-8 xl:grid-cols-2">
        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex items-start justify-between mb-6">
            <h2 class="font-headline-md text-headline-md text-primary">Pedidos recentes</h2>
            <a href="pedidos.php" class="text-sm text-secondary hover:underline">Ver todos</a>
          </div>
          <div class="overflow-x-auto">
            <table class="compact-table min-w-full divide-y divide-outline/20">
              <thead class="bg-primary/10">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-label-caps text-label-caps text-on-surface-variant/60">Pedido</th>
                  <th class="px-4 py-3 text-left text-xs font-label-caps text-label-caps text-on-surface-variant/60">Cliente</th>
                  <th class="px-4 py-3 text-right text-xs font-label-caps text-label-caps text-on-surface-variant/60">Total</th>
                  <th class="px-4 py-3 text-center text-xs font-label-caps text-label-caps text-on-surface-variant/60">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-outline/10">
                <?php foreach ($pedidos_recentes as $pedido): ?>
                  <tr>
                    <td class="px-4 py-3">#<?php echo (int) $pedido['id']; ?><br><span class="text-xs text-on-surface-variant"><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></span></td>
                    <td class="px-4 py-3"><?php echo escapar($pedido['usuario_nome'] ?? 'Cliente'); ?><br><span class="text-xs text-on-surface-variant"><?php echo escapar($pedido['usuario_email'] ?? ''); ?></span></td>
                    <td class="px-4 py-3 text-right"><?php echo formatar_moeda($pedido['total']); ?></td>
                    <td class="px-4 py-3 text-center"><span class="px-3 py-1 bg-primary/10 text-primary text-xs"><?php echo status_pedido_label($pedido['status']); ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex items-start justify-between mb-6">
            <h2 class="font-headline-md text-headline-md text-primary">Melhores clientes</h2>
            <a href="exportar_relatorio.php?tipo=clientes" class="text-sm text-secondary hover:underline">Exportar</a>
          </div>
          <div class="space-y-4">
            <?php if (empty($melhores_clientes)): ?>
              <p class="text-on-surface-variant">Nenhum cliente com pedidos ainda.</p>
            <?php endif; ?>
            <?php foreach ($melhores_clientes as $cliente): ?>
              <div class="flex items-center justify-between gap-4 border-b border-outline/10 pb-4">
                <div>
                  <p class="text-primary"><?php echo escapar($cliente['nome']); ?></p>
                  <p class="text-sm text-on-surface-variant"><?php echo escapar($cliente['email']); ?> - <?php echo numero_dashboard($cliente['pedidos']); ?> pedido(s)</p>
                </div>
                <strong><?php echo formatar_moeda($cliente['total_gasto']); ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="bg-primary text-white rounded-lg p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          <div>
            <p class="font-label-caps text-label-caps text-secondary-fixed-dim uppercase mb-3">Planilhas de controle</p>
            <h2 class="font-headline-md text-headline-md">Exportacoes prontas para Excel</h2>
            <p class="text-white/75 mt-2">Baixe relatÃ³rios de vendas, produtos, clientes, estoque, e-mails e carrinhos abandonados para controle financeiro e operacional.</p>
          </div>
          <div class="flex flex-wrap gap-3">
            <a href="exportar_relatorio.php?tipo=emails" class="bg-white text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.16em]">E-mails</a>
            <a href="exportar_relatorio.php?tipo=carrinhos" class="bg-white text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.16em]">Carrinhos</a>
            <a href="exportar_relatorio.php?tipo=financeiro&dias=<?php echo $dias; ?>" class="bg-white text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.16em]">Financeiro</a>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>

<script>
  const currency = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
  const palette = ['#061b0e', '#735c00', '#2f6f4e', '#8b5e3c', '#2563eb', '#b91c1c', '#64748b'];

  new Chart(document.getElementById('vendasChart'), {
    type: 'line',
    data: {
      labels: <?php echo json_encode($chart_vendas_labels, JSON_UNESCAPED_UNICODE); ?>,
      datasets: [
        {
          label: 'Receita',
          data: <?php echo json_encode($chart_vendas_receita); ?>,
          borderColor: '#061b0e',
          backgroundColor: 'rgba(6, 27, 14, 0.12)',
          fill: true,
          tension: 0.35,
          yAxisID: 'y'
        },
        {
          label: 'Pedidos',
          data: <?php echo json_encode($chart_vendas_pedidos); ?>,
          borderColor: '#735c00',
          backgroundColor: '#735c00',
          tension: 0.35,
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { labels: { boxWidth: 12 } },
        tooltip: {
          callbacks: {
            label: (ctx) => ctx.dataset.label === 'Receita' ? `Receita: ${currency.format(ctx.parsed.y || 0)}` : `Pedidos: ${ctx.parsed.y || 0}`
          }
        }
      },
      scales: {
        y: { beginAtZero: true, ticks: { callback: (value) => currency.format(value) } },
        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { precision: 0 } }
      }
    }
  });

  new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
      labels: <?php echo json_encode($chart_status_labels, JSON_UNESCAPED_UNICODE); ?>,
      datasets: [{ data: <?php echo json_encode($chart_status_data); ?>, backgroundColor: palette }]
    },
    options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
  });

  new Chart(document.getElementById('categoriaChart'), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($chart_categoria_labels, JSON_UNESCAPED_UNICODE); ?>,
      datasets: [{ label: 'Receita', data: <?php echo json_encode($chart_categoria_data); ?>, backgroundColor: '#1b3022' }]
    },
    options: {
      indexAxis: 'y',
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: (ctx) => currency.format(ctx.parsed.x || 0) } }
      },
      scales: { x: { beginAtZero: true, ticks: { callback: (value) => currency.format(value) } } }
    }
  });
</script>
</body>
</html>
