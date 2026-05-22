<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

$titulo_pagina = 'Gerenciar Produtos';
require_once dirname(__DIR__) . '/app/views/includes/head.php';

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'adicionar':
                // Adicionar novo produto
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $preco = floatval($_POST['preco'] ?? 0);
                $estoque = intval($_POST['estoque'] ?? 0);
                $categoria_id = intval($_POST['categoria_id'] ?? 0);

                // Processar upload de imagem
                $imagem_nome = null;
                if (!empty($_FILES['imagem']['name'])) {
                    $upload_result = upload_imagem($_FILES['imagem']);
                    if (!empty($upload_result['erro'])) {
                        $_SESSION['admin_erro'] = $upload_result['erro'];
                    } else {
                        $imagem_nome = $upload_result['nome'];
                    }
                }

                if (empty($_SESSION['admin_erro'])) {
                    if (!empty($nome) && !empty($descricao) && $preco > 0 && $estoque >= 0 && $categoria_id > 0) {
                        if (adicionar_produto($nome, $descricao, $preco, $estoque, $categoria_id, $imagem_nome)) {
                            $_SESSION['admin_sucesso'] = 'Produto adicionado com sucesso!';
                        } else {
                            $_SESSION['admin_erro'] = 'Erro ao adicionar produto.';
                        }
                    } else {
                        $_SESSION['admin_erro'] = 'Por favor, preencha todos os campos corretamente.';
                    }
                }
                header('Location: produtos.php');
                exit();

            case 'editar':
                // Editar produto existente
                $id = intval($_POST['id'] ?? 0);
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $preco = floatval($_POST['preco'] ?? 0);
                $estoque = intval($_POST['estoque'] ?? 0);
                $categoria_id = intval($_POST['categoria_id'] ?? 0);

                // Processar upload de imagem (se houver nova imagem)
                $imagem_nome = null;
                if (!empty($_FILES['imagem']['name'])) {
                    $upload_result = upload_imagem($_FILES['imagem']);
                    if (!empty($upload_result['erro'])) {
                        $_SESSION['admin_erro'] = $upload_result['erro'];
                    } else {
                        $imagem_nome = $upload_result['nome'];
                    }
                }

                if (empty($_SESSION['admin_erro'])) {
                    if (!empty($nome) && !empty($descricao) && $preco > 0 && $estoque >= 0 && $categoria_id > 0) {
                        if (atualizar_produto($id, $nome, $descricao, $preco, $estoque, $categoria_id, $imagem_nome)) {
                            $_SESSION['admin_sucesso'] = 'Produto atualizado com sucesso!';
                        } else {
                            $_SESSION['admin_erro'] = 'Erro ao atualizar produto.';
                        }
                    } else {
                        $_SESSION['admin_erro'] = 'Por favor, preencha todos os campos corretamente.';
                    }
                }
                header('Location: produtos.php');
                exit();

            case 'excluir':
                // Excluir produto
                $id = intval($_POST['id'] ?? 0);
                if ($id > 0 && excluir_produto($id)) {
                    $_SESSION['admin_sucesso'] = 'Produto excluído com sucesso!';
                } else {
                    $_SESSION['admin_erro'] = 'Erro ao excluir produto.';
                }
                header('Location: produtos.php');
                exit();
        }
    }
}

// Buscar produtos com paginação e busca
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 10; // Produtos por página no admin
$offset = ($pagina - 1) * $limite;

if (!empty($busca)) {
    $produtos = buscar_produtos($busca, $limite, $offset);
    $total_produtos = contar_produtos_busca($busca);
} elseif ($categoria_id > 0) {
    $produtos = obter_produtos_por_categoria($categoria_id, $limite, $offset);
    $total_produtos = contar_produtos_por_categoria($categoria_id);
} else {
    $produtos = obter_produtos($limite, $offset);
    $total_produtos = contar_produtos();
}

$total_paginas = ceil($total_produtos / $limite);
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
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"
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
        Gerenciar Produtos
      </div>

      <!-- ICONES -->
      <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
        <a href="produtos.php?acao=adicionar" class="icon-btn">
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
          <h2 class="font-headline-md text-headline-md mb-4">Lista de Produtos</h2>
          <div class="flex justify-between items-center">
            <button
              onclick="document.getElementById('produto-form').classList.toggle('hidden')"
              class="bg-primary-container text-white px-4 py-2 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
            >
              Adicionar Novo Produto
            </button>
          </div>
        </div>

        <!-- Formulário de produto (adicionar/editar) -->
        <div id="produto-form" class="mb-8 hidden bg-surface rounded-lg border border-outline/20 p-6">
          <h3 class="font-headline-sm text-headline-sm mb-4">Dados do Produto</h3>
          <form action="produtos.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="action" id="produto-action" value="adicionar">
            <input type="hidden" name="id" id="produto-id" value="0">

            <div class="grid gap-4 md:grid-cols-2">
              <div>
                <label class="block font-label-caps text-label-caps mb-2">Nome:</label>
                <input
                  type="text"
                  name="nome"
                  id="produto-nome"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  required
                >
              </div>

              <div>
                <label class="block font-label-caps text-label-caps mb-2">Categoria:</label>
                <select
                  name="categoria_id"
                  id="produto-categoria"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  required
                >
                  <option value="">Selecione uma categoria</option>
                  <?php
                  $categorias = obter_categorias();
                  foreach ($categorias as $categoria):
                  ?>
                    <option value="<?php echo $categoria['id']; ?>"><?php echo escapar($categoria['nome']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="md:col-span-2">
                <label class="block font-label-caps text-label-caps mb-2">Descrição:</label>
                <textarea
                  name="descricao"
                  id="produto-descricao"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  rows="4"
                  required
                ></textarea>
              </div>

              <div>
                <label class="block font-label-caps text-label-caps mb-2">Preço:</label>
                <input
                  type="number"
                  name="preco"
                  id="produto-preco"
                  step="0.01"
                  min="0.01"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  required
                >
              </div>

              <div>
                <label class="block font-label-caps text-label-caps mb-2">Estoque:</label>
                <input
                  type="number"
                  name="estoque"
                  id="produto-estoque"
                  min="0"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  required
                >
              </div>

              <div>
                <label class="block font-label-caps text-label-caps mb-2">Imagem:</label>
                <input
                  type="file"
                  name="imagem"
                  id="produto-imagem"
                  class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                  accept="image/*"
                >
                <p class="text-xs text-on-surface-variant/60 mt-1">Formatos permitidos: JPG, PNG, GIF, WEBP (máx 5MB)</p>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button
                type="button"
                id="produto-cancelar"
                class="mr-4 bg-surface-container hover:bg-primary/10 py-2 px-4 font-label-caps text-label-caps tracking-[0.2em] text-primary hover:text-primary transition-all duration-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                id="produto-salvar"
                class="bg-primary-container text-white py-2 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
              >
                Salvar Produto
              </button>
            </div>
          </form>
        </div>

        <!-- Barra de busca e filtros -->
        <div class="mb-6">
          <div class="flex gap-4 md:gap-6">
            <div class="flex-1">
              <form method="GET" action="produtos.php" class="flex gap-2">
                <input
                  type="text"
                  name="busca"
                  value="<?php echo escapar($busca); ?>"
                  placeholder="Buscar produtos..."
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

            <div>
              <form method="GET" action="produtos.php" class="flex gap-2">
                <select
                  name="categoria"
                  id="admin-filtro-categoria"
                  class="flex-1 form-input-bespoke py-3 text-body-md font-body-md text-primary"
                >
                  <option value="">Todas as categorias</option>
                  <?php
                  foreach ($categorias as $categoria):
                    $selected = ($categoria_id == $categoria['id']) ? 'selected' : '';
                  ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo $selected; ?>>
                      <?php echo escapar($categoria['nome']); ?>
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

        <!-- Tabela de produtos -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-outline/20">
            <thead class="bg-primary/10">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Imagem</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Categoria</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Preço</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Estoque</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Data</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-outline/20">
              <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                  <tr class="hover:bg-primary/5 transition-colors">
                    <td class="px-6 py-4 flex items-center">
                      <?php if ($produto['imagem'] && file_exists('../uploads/' . $produto['imagem'])): ?>
                        <img src="../uploads/<?php echo escapar($produto['imagem']); ?>" alt="<?php echo escapar($produto['nome']); ?>" class="w-12 h-12 object-contain rounded-lg border border-outline/10">
                      <?php else: ?>
                        <div class="w-12 h-12 bg-surface-container flex items-center justify-center rounded-lg">
                          <span class="material-symbols-outlined text-on-surface-variant/60">inventory_2</span>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md">
                      <?php echo escapar($produto['nome']); ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md">
                      <?php echo escapar($produto['categoria_nome'] ?? 'Sem categoria'); ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right">
                      <?php echo formatar_moeda($produto['preco']); ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-center">
                      <span class="px-3 py-1 text-xs font-label-caps
                        <?php
                        if ($produto['estoque'] <= 0) {
                          echo 'bg-red-500/20 text-red-600';
                        } elseif ($produto['estoque'] < 10) {
                          echo 'bg-yellow-500/20 text-yellow-600';
                        } else {
                          echo 'bg-green-500/20 text-green-600';
                        }
                        ?>">
                        <?php echo $produto['estoque']; ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right">
                      <?php
                      $data = new DateTime($produto['data_criacao']);
                      echo $data->format('d/m/Y');
                      ?>
                    </td>
                    <td class="px-6 py-4 text-font-body-md text-body-md text-right space-x-3">
                      <button
                        onclick="editarProduto(<?php echo $produto['id']; ?>)"
                        class="bg-primary-container text-white px-3 py-1 text-xs font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                      >
                        Editar
                      </button>
                      <button
                        onclick="excluirProduto(<?php echo $produto['id']; ?>)"
                        class="bg-red-500/20 text-red-600 px-3 py-1 text-xs font-label-caps text-label-caps tracking-[0.2em] hover:bg-red-500/30 transition-all duration-300"
                      >
                        Excluir
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td class="px-6 py-8 text-center text-on-surface-variant/60" colspan="7">
                    Nenhum produto encontrado.
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
                  href="produtos.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>"
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
                  href="produtos.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>"
                  class="px-4 py-2 <?php echo $i == $pagina ? 'bg-primary text-white' : 'bg-surface-container text-primary hover:bg-primary/10'; ?> font-label-caps text-label-caps tracking-[0.2em] transition-all duration-300"
                >
                  <?php echo $i; ?>
                </a>
              <?php endfor; ?>

              <?php if ($pagina < $total_paginas): ?>
                <a
                  href="produtos.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>"
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
// Função para editar produto
function editarProduto(id) {
    // Buscar os dados do produto via AJAX (simplificado - em produção usaríamos AJAX real)
    // Por enquanto, vamos apenas abrir o formulário e deixar o usuário preencher manualmente
    // Em uma implementação real, faríamos uma requisição para obter os dados do produto

    // Abrir o formulário
    document.getElementById('produto-form').classList.remove('hidden');
    document.getElementById('produto-action').value = 'editar';
    document.getElementById('produto-id').value = id;

    // Limpar formulário e focar no primeiro campo
    document.getElementById('produto-form').reset();
    document.getElementById('produto-nome').focus();

    // Alterar texto do botão
    document.getElementById('produto-salvar').textContent = 'Atualizar Produto';

    // Nota: Em uma implementação completa, buscaríamos os dados do produto via AJAX
    // e preencheríamos o formulário automaticamente
}

// Função para excluir produto
function excluirProduto(id) {
    if (confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.')) {
        // Criar formulário temporário para submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'produtos.php';

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
document.getElementById('produto-cancelar').addEventListener('click', function() {
    document.getElementById('produto-form').classList.add('hidden');
    document.getElementById('produto-action').value = 'adicionar';
    document.getElementById('produto-id').value = '0';
    document.getElementById('produto-form').reset();
    document.getElementById('produto-salvar').textContent = 'Salvar Produto';
});

// Fecha o formulário ao clicar fora (opcional)
document.addEventListener('click', function(e) {
    const form = document.getElementById('produto-form');
    const button = document.querySelector('button[onclick*="produto-form"]');
    if (!form.contains(e.target) && !button.contains(e.target) && !form.classList.contains('hidden')) {
        // Clicou fora do formulário e do botão de abrir
        form.classList.add('hidden');
        document.getElementById('produto-action').value = 'adicionar';
        document.getElementById('produto-id').value = '0';
        document.getElementById('produto-form').reset();
        document.getElementById('produto-salvar').textContent = 'Salvar Produto';
    }
});
</script>

<?php
require_once dirname(__DIR__) . '/app/views/includes/footer.php';
?>