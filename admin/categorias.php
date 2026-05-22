<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

$titulo_pagina = 'Gerenciar Categorias';
require_once dirname(__DIR__) . '/app/views/includes/head.php';

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'adicionar':
                // Adicionar nova categoria
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');

                if (!empty($nome)) {
                    if (adicionar_categoria($nome, $descricao)) {
                        $_SESSION['admin_sucesso'] = 'Categoria adicionada com sucesso!';
                    } else {
                        $_SESSION['admin_erro'] = 'Erro ao adicionar categoria.';
                    }
                } else {
                    $_SESSION['admin_erro'] = 'Nome da categoria é obrigatório.';
                }
                header('Location: categorias.php');
                exit();

            case 'editar':
                // Editar categoria existente
                $id = intval($_POST['id'] ?? 0);
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');

                if (!empty($nome)) {
                    if (atualizar_categoria($id, $nome, $descricao)) {
                        $_SESSION['admin_sucesso'] = 'Categoria atualizada com sucesso!';
                    } else {
                        $_SESSION['admin_erro'] = 'Erro ao atualizar categoria.';
                    }
                } else {
                    $_SESSION['admin_erro'] = 'Nome da categoria é obrigatório.';
                }
                header('Location: categorias.php');
                exit();

            case 'excluir':
                // Excluir categoria
                $id = intval($_POST['id'] ?? 0);
                if ($id > 0 && excluir_categoria($id)) {
                    $_SESSION['admin_sucesso'] = 'Categoria excluída com sucesso!';
                } else {
                    $_SESSION['admin_erro'] = 'Erro ao excluir categoria.';
                }
                header('Location: categorias.php');
                exit();
        }
    }
}

// Função para obter todas as categorias (já existe em funcoes.php, mas vamos garantir)
function obter_categorias_admin() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
    return $stmt->fetchAll();
}

// Função para obter categoria por ID
function obter_categoria_por_id_admin($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Função para adicionar categoria
function adicionar_categoria($nome, $descricao = '') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO categorias (nome, descricao, data_criacao) VALUES (?, ?, NOW())");
    return $stmt->execute([$nome, $descricao]);
}

// Função para atualizar categoria
function atualizar_categoria($id, $nome, $descricao = '') {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?");
    return $stmt->execute([$nome, $descricao, $id]);
}

// Função para excluir categoria
function excluir_categoria($id) {
    global $pdo;
    // Verificar se há produtos associados
    $stmt_check = $pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?");
    $stmt_check->execute([$id]);
    if ($stmt_check->fetch()['total'] > 0) {
        return false; // Não pode excluir se houver produtos associados
    }
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    return $stmt->execute([$id]);
}

// Buscar categorias com paginação e busca
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 10; // Categorias por página no admin
$offset = ($pagina - 1) * $limite;

if (!empty($busca)) {
    // Busca por nome ou descrição
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE nome LIKE ? OR descricao LIKE ? ORDER BY nome");
    $busca_term = "%$busca%";
    $stmt->execute([$busca_term, $busca_term]);
    $categorias = $stmt->fetchAll();

    // Contar total para paginação
    $stmt_count = $pdo->prepare("SELECT COUNT(*) as total FROM categorias WHERE nome LIKE ? OR descricao LIKE ?");
    $stmt_count->execute([$busca_term, $busca_term]);
    $total_categorias = $stmt_count->fetch()['total'];
} else {
    $categorias = obter_categorias_admin();
    $total_categorias = contar_categorias();

    // Aplicar paginação manualmente (como obter_categorias_admin não aceita limite/offset)
    $categorias = array_slice($categorias, $offset, $limite);
}

$total_paginas = ceil($total_categorias / $limite);
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
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"
    >
      <span class="material-symbols-outlined">category</span>
      <span class="ml-3">Categorias</span>
    </a>
    <a
      href="pedidos.php"
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">list_alt</span>
      <span class="ml-3">Pedidos</span>
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
        Gerenciar Categorias
      </div>

      <!-- ICONES -->
      <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
        <a href="categorias.php?acao=adicionar" class="icon-btn">
          <span class="material-symbols-outlined">add</span>
        </a>
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
          <h2 class="font-headline-md text-headline-md mb-4">Lista de Categorias</h2>
          <div class="flex justify-between items-center">
            <button
              onclick="document.getElementById('categoria-form').classList.toggle('hidden')"
              class="bg-primary-container text-white px-4 py-2 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
            >
              Adicionar Nova Categoria
            </button>
          </div>
        </div>

        <!-- Formulário de categoria (adicionar/editar) -->
        <div id="categoria-form" class="mb-8 hidden bg-surface rounded-lg border border-outline/20 p-6">
          <h3 class="font-headline-sm text-headline-sm mb-4">Dados da Categoria</h3>
          <form action="categorias.php" method="POST" class="space-y-6">
            <input type="hidden" name="action" id="categoria-action" value="adicionar">
            <input type="hidden" name="id" id="categoria-id" value="0">

            <div class="space-y-4">
              <div>
                <label class="block font-label-caps text-label-caps mb-2">Nome:</label>
                <input
                  type="text"
                  name="nome"
                  id="categoria-nome"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  required
                >
              </div>

              <div>
                <label class="block font-label-caps text-label-caps mb-2">Descrição:</label>
                <textarea
                  name="descricao"
                  id="categoria-descricao"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  rows="4"
                ></textarea>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button
                type="button"
                id="categoria-cancelar"
                class="mr-4 bg-surface-container hover:bg-primary/10 py-2 px-4 font-label-caps text-label-caps tracking-[0.2em] text-primary hover:text-primary transition-all duration-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                id="categoria-salvar"
                class="bg-primary-container text-white py-2 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
              >
                Salvar Categoria
              </button>
            </div>
          </form>
        </div>

        <!-- Barra de busca -->
        <div class="mb-6">
          <form method="GET" action="categorias.php" class="flex gap-2">
            <input
              type="text"
              name="busca"
              value="<?php echo escapar($busca); ?>"
              placeholder="Buscar categorias..."
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

        <!-- Tabela de categorias -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-outline/20">
            <thead class="bg-primary/10">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">ID</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Descrição</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Produtos Associados</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Data</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-outline/20">
              <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                  <?php
                  // Contar produtos associados
                  $stmt_prod = $pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?");
                  $stmt_prod->execute([$categoria['id']]);
                  $total_produtos = $stmt_prod->fetch()['total'];
                  ?>
                  <tr class="hover:bg-primary/5 transition-colors">
                    <td class="px-6 py-4 text-center text-font-body-md text-body-md">
                      <?php echo $categoria['id']; ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md">
                      <?php echo escapar($categoria['nome']); ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md">
                      <?php echo escapar($categoria['descricao'] ?? '-'); ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-center">
                      <span class="px-3 py-1 text-xs font-label-caps
                        <?php
                        if ($total_produtos == 0) {
                          echo 'bg-yellow-500/20 text-yellow-600';
                        } else {
                          echo 'bg-green-500/20 text-green-600';
                        }
                        ?>">
                        <?php echo $total_produtos; ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right">
                      <?php
                      $data = new DateTime($categoria['data_criacao']);
                      echo $data->format('d/m/Y');
                      ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right space-x-3">
                      <button
                        onclick="editarCategoria(<?php echo $categoria['id']; ?>)"
                        class="bg-primary-container text-white px-3 py-1 text-xs font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                      >
                        Editar
                      </button>
                      <button
                        onclick="excluirCategoria(<?php echo $categoria['id']; ?>)"
                        class="bg-red-500/20 text-red-600 px-3 py-1 text-xs font-label-caps text-label-caps tracking-[0.2em] hover:bg-red-500/30 transition-all duration-300"
                        <?php echo $total_produtos > 0 ? 'disabled' : ''; ?>
                      >
                        Excluir
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td class="px-6 py-8 text-center text-on-surface-variant/60" colspan="6">
                    Nenhuma categoria encontrada.
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
                  href="categorias.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>"
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
                  href="categorias.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>"
                  class="px-4 py-2 <?php echo $i == $pagina ? 'bg-primary text-white' : 'bg-surface-container text-primary hover:bg-primary/10'; ?> font-label-caps text-label-caps tracking-[0.2em] transition-all duration-300"
                >
                  <?php echo $i; ?>
                </a>
              <?php endfor; ?>

              <?php if ($pagina < $total_paginas): ?>
                <a
                  href="categorias.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>"
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

<script>
// Função para editar categoria
function editarCategoria(id) {
    // Buscar os dados da categoria via AJAX (simplificado)
    // Por enquanto, vamos apenas abrir o formulário e deixar o usuário preencher manualmente
    document.getElementById('categoria-form').classList.remove('hidden');
    document.getElementById('categoria-action').value = 'editar';
    document.getElementById('categoria-id').value = id;

    // Limpar formulário
    document.getElementById('categoria-form').reset();
    document.getElementById('categoria-nome').focus();

    // Alterar texto do botão
    document.getElementById('categoria-salvar').textContent = 'Atualizar Categoria';

    // Nota: Em uma implementação completa, buscaríamos os dados da categoria via AJAX
    // e preencheríamos o formulário automaticamente
}

// Função para excluir categoria
function excluirCategoria(id) {
    if (confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')) {
        // Criar formulário temporário para submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'categorias.php';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'excluir';

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;

        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para cancelar edição
document.getElementById('categoria-cancelar').addEventListener('click', function() {
    document.getElementById('categoria-form').classList.add('hidden');
    document.getElementById('categoria-action').value = 'adicionar';
    document.getElementById('categoria-id').value = '0';
    document.getElementById('categoria-form').reset();
    document.getElementById('categoria-salvar').textContent = 'Salvar Categoria';
});

// Fecha o formulário ao clicar fora (opcional)
document.addEventListener('click', function(e) {
    const form = document.getElementById('categoria-form');
    const button = document.querySelector('button[onclick*="categoria-form"]');
    if (!form.contains(e.target) && !button.contains(e.target) && !form.classList.contains('hidden')) {
        // Clicou fora do formulário e do botão de abrir
        form.classList.add('hidden');
        document.getElementById('categoria-action').value = 'adicionar';
        document.getElementById('categoria-id').value = '0';
        document.getElementById('categoria-form').reset();
        document.getElementById('categoria-salvar').textContent = 'Salvar Categoria';
    }
});
</script>

<?php
require_once dirname(__DIR__) . '/app/views/includes/footer.php';
?>