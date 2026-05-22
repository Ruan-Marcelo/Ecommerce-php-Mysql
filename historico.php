<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';
proteger_pagina(); // Redireciona se não estiver logado

$titulo_pagina = 'Meus Pedidos';
require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';

// Parâmetros de paginação
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 5; // Pedidos por página
$offset = ($pagina - 1) * $limite;

// Obter pedidos do usuário
$pedidos = obter_pedidos_usuario($_SESSION['usuario_id'], $limite, $offset);
$total_pedidos = contar_pedidos_usuario($_SESSION['usuario_id']);
$total_paginas = ceil($total_pedidos / $limite);
?>
<div class="flex-grow flex items-center justify-center py-section-gap px-gutter">
  <div class="max-w-container-max w-full">
    <?php
    // Exibir mensagens
    if (isset($_SESSION['finalizar_sucesso'])) {
        echo mensagem_sucesso($_SESSION['finalizar_sucesso']);
        unset($_SESSION['finalizar_sucesso']);
    }
    if (isset($_SESSION['historico_erro'])) {
        echo mensagem_erro($_SESSION['historico_erro']);
        unset($_SESSION['historico_erro']);
    }
    ?>

    <div class="bg-surface rounded-lg border border-outline/20 p-6">
      <h2 class="font-headline-md text-headline-md mb-6">Meus Pedidos</h2>

      <?php if (empty($pedidos)): ?>
        <div class="text-center py-12">
          <p class="text-on-surface-variant/60">Você ainda não realizou nenhum pedido.</p>
          <a href="produtos.php" class="mt-4 inline-block text-primary hover:underline">Começar a comprar</a>
        </div>
      <?php else: ?>
        <div class="space-y-6">
          <?php foreach ($pedidos as $pedido): ?>
            <div class="border border-outline/20 rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="font-headline-sm text-headline-sm">Pedido #<?php echo $pedido['id']; ?></h3>
                  <p class="text-sm text-on-surface-variant/60">
                    <?php
                    $data = new DateTime($pedido['data_pedido']);
                    echo $data->format('d/m/Y H:i');
                    ?>
                  </p>
                </div>
                <div class="text-right">
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
                    ?>
                    ">
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
                </div>
              </div>

              <div class="space-y-4">
                <?php
                $itens = obter_itens_pedido($pedido['id']);
                if (!empty($itens)):
                ?>
                  <div class="space-y-3">
                    <?php foreach ($itens as $item): ?>
                      <div class="flex items-start gap-4 py-2 border-b border-outline/10 last:border-b-0">
                        <!-- Imagem do produto -->
                        <div class="flex-shrink-0">
                          <?php if (imagem_produto_disponivel($item['produto_imagem'] ?? '')): ?>
                            <img src="<?php echo escapar(imagem_produto_url($item['produto_imagem'])); ?>" alt="<?php echo escapar($item['produto_nome']); ?>" class="w-16 h-16 object-contain rounded-lg">
                          <?php else: ?>
                            <div class="w-16 h-16 bg-surface-container flex items-center justify-center rounded-lg">
                              <span class="material-symbols-outlined text-on-surface-variant/60">inventory_2</span>
                            </div>
                          <?php endif; ?>
                        </div>

                        <!-- Informações do item -->
                        <div class="flex-1 space-y-1">
                          <p class="font-body-sm text-body-sm line-clamp-1"><?php echo escapar($item['produto_nome']); ?></p>
                          <p class="text-xs text-on-surface-variant/60">
                            <?php echo $item['quantidade']; ?>x <?php echo formatar_moeda($item['preco_unitario']); ?>
                          </p>
                        </div>

                        <!-- Subtotal do item -->
                        <div class="flex-shrink-0 text-right">
                          <p class="font-body-sm text-body-sm"><?php echo formatar_moeda($item['quantidade'] * $item['preco_unitario']); ?></p>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

              <div class="mt-4 pt-3 border-t border-outline/20">
                <div class="flex justify-between">
                  <span class="font-label-caps text-label-caps">Total:</span>
                  <span class="font-headline-md text-headline-md text-primary">
                    <?php echo formatar_moeda($pedido['total']); ?>
                  </span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Paginação -->
        <?php if ($total_paginas > 1): ?>
          <div class="mt-8">
            <nav class="flex flex-wrap justify-center gap-2">
              <?php if ($pagina > 1): ?>
                <a
                  href="historico.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>"
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
                  href="historico.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>"
                  class="px-4 py-2 <?php echo $i == $pagina ? 'bg-primary text-white' : 'bg-surface-container text-primary hover:bg-primary/10'; ?> font-label-caps text-label-caps tracking-[0.2em] transition-all duration-300"
                >
                  <?php echo $i; ?>
                </a>
              <?php endfor; ?>

              <?php if ($pagina < $total_paginas): ?>
                <a
                  href="historico.php?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>"
                  class="px-4 py-2 bg-primary-container text-white font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                >
                  Próxima
                </a>
              <?php endif; ?>
            </nav>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/app/views/includes/footer.php';
?>
