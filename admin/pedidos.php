<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

$titulo_pagina = 'Gerenciar Pedidos';
global $pdo;

// Processar ações (atualizar status do pedido)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'atualizar_status') {
        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        // Validar status
        $statuses_permitidos = ['pendente', 'processando', 'enviado', 'entregue', 'cancelado'];
        if ($id > 0 && in_array($status, $statuses_permitidos)) {
            global $pdo;
            $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
            if ($stmt->execute([$status, $id])) {
                $_SESSION['admin_sucesso'] = 'Status do pedido atualizado com sucesso!';
            } else {
                $_SESSION['admin_erro'] = 'Erro ao atualizar status do pedido.';
            }
        } else {
            $_SESSION['admin_erro'] = 'Dados inválidos para atualização de status.';
        }
        header('Location: pedidos.php');
        exit();
    }
}

// Buscar pedidos com paginação e busca
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$status_filtro = isset($_GET['status']) ? $_GET['status'] : '';
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 10; // Pedidos por página no admin
$offset = ($pagina - 1) * $limite;

// Construir query com filtros
$where = [];
$params = [];

if (!empty($busca)) {
    $where[] = "(p.id LIKE ? OR u.nome LIKE ? OR u.email LIKE ?)";
    $busca_term = "%$busca%";
    $params[] = $busca_term;
    $params[] = $busca_term;
    $params[] = $busca_term;
}

if (!empty($status_filtro)) {
    $where[] = "p.status = ?";
    $params[] = $status_filtro;
}

$where_clause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);

// Contar total de pedidos para paginação
$stmt_count = $pdo->prepare("SELECT COUNT(*) as total FROM pedidos p LEFT JOIN usuarios u ON p.usuario_id = u.id $where_clause");
$stmt_count->execute($params);
$total_pedidos = $stmt_count->fetch()['total'];

// Buscar pedidos
$stmt = $pdo->prepare("SELECT p.*, u.nome as usuario_nome, u.email as usuario_email FROM pedidos p LEFT JOIN usuarios u ON p.usuario_id = u.id $where_clause ORDER BY p.data_pedido DESC LIMIT ? OFFSET ?");
foreach ($params as $index => $valor) {
    $stmt->bindValue($index + 1, $valor);
}
$stmt->bindValue(count($params) + 1, $limite, PDO::PARAM_INT);
$stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll();

$total_paginas = ceil($total_pedidos / $limite);
require_once dirname(__DIR__) . '/app/views/includes/head.php';
?>
<!-- Admin Sidebar -->
<aside
  class="fixed top-0 left-0 h-full w-64 bg-primary text-on-primary z-40 flex flex-col"
>
  <div class="flex items-center justify-center py-8">
    <div
      class="text-xl font-headline-lg tracking-[0.4em] text-white"
    >
      LUPIÈRE ADMIN
    </div>
  </div>
  <nav class="flex-1 flex-col pt-6 space-y-4">
    <a
      href="index.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">dashboard</span>
      <span class="ml-3">Dashboard</span>
    </a>
    <a
      href="produtos.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">inventory_2</span>
      <span class="ml-3">Produtos</span>
    </a>
    <a
      href="categorias.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">category</span>
      <span class="ml-3">Categorias</span>
    </a>
    <a
      href="pedidos.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"
    >
      <span class="material-symbols-outlined">list_alt</span>
      <span class="ml-3">Pedidos</span>
    </a>
    <a
      href="banners.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">image</span>
      <span class="ml-3">Banners</span>
    </a>
    <a
      href="administradores.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">admin_panel_settings</span>
      <span class="ml-3">Administradores</span>
    </a>
    <a
      href="../logout.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors mt-auto"
    >
      <span class="material-symbols-outlined">logout</span>
      <span class="ml-3">Sair</span>
    </a>
  </nav>
</aside>
<!-- Main Content -->
<main
  class="flex-grow ml-64 flex flex-col"
>
  <!-- TopNavBar -->
  <header
    class="fixed top-0 left-64 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-16 flex items-center"
  >
    <div
      class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto"
    >
      <!-- LOGO -->
      <div
        class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]"
      >
        Gerenciar Pedidos
      </div>

      <!-- ICONES -->
      <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
        <span class="material-symbols-outlined">search</span>
      </div>
    </div>
  </header>

  <!-- Dashboard Content -->
  <div class="flex-grow flex items-center justify-center py-section-gap px-gutter">
    <div class="max-w-container-max w-full">
      <?php
      // Exibir mensagens
      if (isset($_SESSION['admin_sucesso'])) {
          echo mensagem_sucesso($_SESSION['admin_sucesso']);
          unset($_SESSION['admin_sucesso']);
      }
      if (isset($_SESSION['admin_erro'])) {
          echo mensagem_erro($_SESSION['admin_erro']);
          unset($_SESSION['admin_erro']);
      }
      ?>

      <div class="bg-surface rounded-lg border border-outline/20 p-6">
        <div class="mb-6">
          <h2 class="font-headline-md text-headline-md mb-4">Lista de Pedidos</h2>
          <div class="flex justify-between items-center">
            <!-- Barra de busca e filtros -->
            <div class="flex gap-4 md:gap-6 w-full max-w-2xl">
              <div class="flex-1">
                <form method="GET" action="pedidos.php" class="flex gap-2">
                  <input
                    type="text"
                    name="busca"
                    value="<?php echo escapar($busca); ?>"
                    placeholder="Buscar pedidos..."
                    class="flex-1 form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  >
                  <button
                    type="submit"
                    class="bg-primary-container text-white py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                  >
                    Buscar
                  </button>
                </form>
              </div>

              <div class="flex-1">
                <form method="GET" action="pedidos.php" class="flex gap-2">
                  <select
                    name="status"
                    id="filtro-status"
                    class="flex-1 form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  >
                    <option value="">Todos os status</option>
                    <?php
                    $statuses = ['pendente' => 'Pendente', 'processando' => 'Processando', 'enviado' => 'Enviado', 'entregue' => 'Entregue', 'cancelado' => 'Cancelado'];
                    foreach ($statuses as $value => $label):
                      $selected = ($status_filtro == $value) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $value; ?>" <?php echo $selected; ?>>
                        <?php echo $label; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <input
                    type="hidden"
                    name="busca"
                    value="<?php echo escapar($busca); ?>">
                  <button
                    type="submit"
                    class="bg-primary-container text-white py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                  >
                    Filtrar
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabela de pedidos -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-outline/20">
            <thead class="bg-primary/10">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">#</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Data</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Total</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Status</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Itens</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-outline/20">
              <?php if (!empty($pedidos)): ?>
                <?php foreach ($pedidos as $pedido): ?>
                  <tr class="hover:bg-primary/5 transition-colors">
                    <td class="px-6 py-4 text-center text-font-body-md text-body-md">
                      #<?php echo $pedido['id']; ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md">
                      <?php echo escapar($pedido['usuario_nome']); ?>
                      <br>
                      <span class="text-xs text-on-surface-variant/60"><?php echo escapar($pedido['usuario_email']); ?></span>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-center">
                      <?php
                      $data = new DateTime($pedido['data_pedido']);
                      echo $data->format('d/m/Y H:i');
                      ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right">
                      <?php echo formatar_moeda($pedido['total']); ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-center">
                      <span class="px-3 py-1 text-xs font-label-caps
                        <?php
                        switch ($pedido['status']) {
                            case 'pendente': echo 'bg-yellow-500/20 text-yellow-600'; break;
                            case 'processando': echo 'bg-blue-500/20 text-blue-600'; break;
                            case 'enviado': echo 'bg-indigo-500/20 text-indigo-600'; break;
                            case 'entregue': echo 'bg-green-500/20 text-green-600'; break;
                            case 'cancelado': echo 'bg-red-500/20 text-red-600'; break;
                            default: echo 'bg-gray-500/20 text-gray-600';
                        }
                        ?>">
                        <?php
                        switch ($pedido['status']) {
                            case 'pendente': echo 'Pendente'; break;
                            case 'processando': echo 'Processando'; break;
                            case 'enviado': echo 'Enviado'; break;
                            case 'entregue': echo 'Entregue'; break;
                            case 'cancelado': echo 'Cancelado'; break;
                            default: echo $pedido['status'];
                        }
                        ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-center">
                      <!-- Ver itens do pedido -->
                      <button
                        onclick="verItensPedido(<?php echo $pedido['id']; ?>)"
                        class="bg-primary-container text-white px-3 py-1 text-xs font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                      >
                        Ver
                      </button>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right space-x-3">
                      <button
                        onclick="atualizarStatusPedido(<?php echo $pedido['id']; ?>)"
                        class="bg-primary-container text-white px-3 py-1 text-xs font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                      >
                        Atualizar Status
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td class="px-6 py-8 text-center text-on-surface-variant/60" colspan="7">
                    Nenhum pedido encontrado.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginação -->
        <?php if ($total_paginas > 1): ?>
          <div class="mt-6">
            <nav class="flex flex-wrap justify-center gap-2">
              <?php if ($pagina > 1): ?>
                <a
                  href="pedidos.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>"
                  class="px-4 py-2 bg-primary-container text-white font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                >
                  Anterior
                </a>
              <?php endif; ?>

              <?php
              // Mostrar números das páginas (máximo 5 visibles)
              $inicio_pagina = max(1, $pagina - 2);
              $fim_pagina = min($total_paginas, $inicio_pagina + 4);
              if ($fim_pagina - $inicio_pagina < 4) {
                $inicio_pagina = max(1, $fim_pagina - 4);
              }
              ?>

              <?php for ($i = $inicio_pagina; $i <= $fim_pagina; $i++): ?>
                <a
                  href="pedidos.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>"
                  class="px-4 py-2 <?php echo $i == $pagina ? 'bg-primary text-white' : 'bg-surface-container text-primary hover:bg-primary/10'; ?> font-label-caps text-label-caps tracking-[0.2em] transition-all duration-300"
                >
                  <?php echo $i; ?>
                </a>
              <?php endfor; ?>

              <?php if ($pagina < $total_paginas): ?>
                <a
                  href="pedidos.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>"
                  class="px-4 py-2 bg-primary-container text-white font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                >
                  Próxima
                </a>
              <?php endif; ?>
            </nav>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<!-- Modal para ver itens do pedido -->
<div id="modal-itens" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
  <div class="bg-surface rounded-lg border border-outline/20 p-6 max-w-2xl w-full mx-4">
    <div class="flex justify-between items-start mb-4">
      <h3 class="font-headline-sm text-headline-sm">Itens do Pedido #<span id="modal-pedido-id"></span></h3>
      <button
        onclick="document.getElementById('modal-itens').classList.add('hidden')"
        class="text-xs text-on-surface-variant/60 hover:text-primary"
      >
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div id="modal-itens-content" class="space-y-4">
      <!-- Conteúdo será carregado via JavaScript -->
    </div>
  </div>
</div>

<!-- Modal para atualizar status do pedido -->
<div id="modal-status" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
  <div class="bg-surface rounded-lg border border-outline/20 p-6 max-w-md w-full mx-4">
    <div class="flex justify-between items-start mb-4">
      <h3 class="font-headline-sm text-headline-sm">Atualizar Status do Pedido #<span id="modal-status-pedido-label"></span></h3>
      <button
        onclick="document.getElementById('modal-status').classList.add('hidden')"
        class="text-xs text-on-surface-variant/60 hover:text-primary"
      >
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <form id="form-atualizar-status" class="space-y-4">
      <div class="space-y-2">
        <label class="block font-label-caps text-label-caps mb-2">Novo Status:</label>
        <select
          name="status"
          id="modal-status-select"
          class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
          required
        >
          <option value="">Selecione um status</option>
          <option value="pendente">Pendente</option>
          <option value="processando">Processando</option>
          <option value="enviado">Enviado</option>
          <option value="entregue">Entregue</option>
          <option value="cancelado">Cancelado</option>
        </select>
      </div>
      <div class="flex justify-end">
        <button
          type="button"
          id="modal-status-cancelar"
          class="mr-4 bg-surface-container hover:bg-primary/10 py-2 px-4 font-label-caps text-label-caps tracking-[0.2em] text-primary hover:text-primary transition-all duration-300"
        >
          Cancelar
        </button>
        <button
          type="submit"
          id="modal-status-salvar"
          class="bg-primary-container text-white py-2 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
        >
          Atualizar Status
        </button>
      </div>
      <input type="hidden" id="modal-status-pedido-id" name="id">
    </form>
  </div>
</div>

<script>
// Função para ver itens do pedido
function verItensPedido(pedidoId) {
    // Mostrar loading
    document.getElementById('modal-itens-content').innerHTML = '<div class="text-center py-8"><span class="material-symbols-outlined">hourglass_bottom</span> <p class="mt-2 text-on-surface-variant/60">Carregando itens...</p></div>';
    document.getElementById('modal-pedido-id').textContent = pedidoId;
    document.getElementById('modal-itens').classList.remove('hidden');

    // Fazer requisição AJAX para obter os itens do pedido
    fetch('get_itens_pedido.php?id=' + pedidoId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let content = '';
                if (data.itens.length > 0) {
                    data.itens.forEach(item => {
                        const nome = escaparHtml(item.produto_nome || 'Produto sem nome');
                        const imagem = item.produto_imagem || '';
                        const quantidade = Number(item.quantidade || 0);
                        const precoUnitario = Number(item.preco_unitario || 0);
                        const subtotal = quantidade * precoUnitario;
                        const imagemHtml = imagem
                            ? `<img src="../public/uploads/${encodeURIComponent(imagem)}" alt="${nome}" class="w-16 h-16 object-contain rounded-lg border border-outline/10">`
                            : `<div class="w-16 h-16 bg-surface-container flex items-center justify-center rounded-lg">
                                  <span class="material-symbols-outlined text-on-surface-variant/60">inventory_2</span>
                               </div>`;

                        content += `
                            <div class="border border-outline/20 rounded-lg p-4 flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    ${imagemHtml}
                                </div>
                                <div class="flex-1 space-y-1">
                                    <p class="font-body-sm text-body-sm line-clamp-1">${nome}</p>
                                    <p class="text-xs text-on-surface-variant/60">
                                        ${quantidade}x ${formatarMoeda(precoUnitario)}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="font-body-sm text-body-sm">${formatarMoeda(subtotal)}</p>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    content = '<p class="text-center text-on-surface-variant/60 py-8">Nenhum item encontrado para este pedido.</p>';
                }
                document.getElementById('modal-itens-content').innerHTML = content;
            } else {
                document.getElementById('modal-itens-content').innerHTML = '<p class="text-center text-on-surface-variant/60 py-8">Erro ao carregar itens: ' + data.message + '</p>';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('modal-itens-content').innerHTML = '<p class="text-center text-on-surface-variant/60 py-8">Erro de conexão</p>';
        });
}

function escaparHtml(valor) {
    return String(valor).replace(/[&<>"']/g, function(caractere) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[caractere];
    });
}

function formatarMoeda(valor) {
    return Number(valor || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

// Função para atualizar status do pedido
function atualizarStatusPedido(pedidoId) {
    document.getElementById('modal-status-pedido-id').value = pedidoId;
    document.getElementById('modal-status-pedido-label').textContent = pedidoId;
    document.getElementById('modal-status-select').value = ''; // Resetar seleção
    document.getElementById('modal-status').classList.remove('hidden');
}

// Event listener para o formulário de atualização de status
document.getElementById('form-atualizar-status').addEventListener('submit', function(e) {
    e.preventDefault();

    const pedidoId = document.getElementById('modal-status-pedido-id').value;
    const status = document.getElementById('modal-status-select').value;

    if (!pedidoId || !status) {
        alert('Por favor, selecione um status válido.');
        return;
    }

    // Enviar requisição POST para atualizar o status
    fetch('pedidos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'atualizar_status',
            id: pedidoId,
            status: status
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao atualizar status');
        }
        document.getElementById('modal-status').classList.add('hidden');
        window.location.reload();
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão');
    });
});

// Event listener para cancelar modais
document.getElementById('modal-status-cancelar').addEventListener('click', function() {
    document.getElementById('modal-status').classList.add('hidden');
});

document.querySelector('#modal-itens button[onclick*="modal-itens"]').addEventListener('click', function() {
    document.getElementById('modal-itens').classList.add('hidden');
});

// Fecha os modais ao clicar fora
window.addEventListener('click', function(e) {
    const modalItens = document.getElementById('modal-itens');
    const modalStatus = document.getElementById('modal-status');

    if (e.target === modalItens) {
        modalItens.classList.add('hidden');
    }

    if (e.target === modalStatus) {
        modalStatus.classList.add('hidden');
    }
});
</script>
