<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin(); // Redireciona se não estiver logado ou não for admin

$titulo_pagina = 'Dashboard';
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
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"
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
      class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
    >
      <span class="material-symbols-outlined">list_alt</span>
      <span class="ml-3">Pedidos</span>
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
        Dashboard
      </div>

      <!-- ICONES -->
      <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
        <span class="material-symbols-outlined">account_circle</span>
        <span class="ml-2 text-on-surface-variant/60">
          <?php
          $usuario = obter_usuario_por_id($_SESSION['usuario_id']);
          echo escapar($usuario['nome'] ?? 'Usuário');
          ?>
        </span>
      </div>
    </div>
  </header>

  <!-- Dashboard Content -->
  <div class="flex-grow flex items-center justify-center py-section-gap px-gutter">
    <div class="max-w-container-max w-full">
      <div class="space-y-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <!-- Total Products -->
          <div class="bg-surface rounded-lg border border-outline/20 p-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-label-caps text-label-caps text-on-surface-variant/60">Total de Produtos</p>
                <p class="font-headline-md text-headline-md text-primary">
                  <?php
                  $total_produtos = contar_produtos();
                  echo $total_produtos;
                  ?>
                </p>
              </div>
              <div class="flex h-12 w-12 items-center justify-center bg-primary/20 text-primary rounded-lg">
                <span class="material-symbols-outlined">inventory_2</span>
              </div>
            </div>
          </div>

          <!-- Total Categories -->
          <div class="bg-surface rounded-lg border border-outline/20 p-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-label-caps text-label-caps text-on-surface-variant/60">Total de Categorias</p>
                <p class="font-headline-md text-headline-md text-primary">
                  <?php
                  $total_categorias = contar_categorias();
                  echo $total_categorias;
                  ?>
                </p>
              </div>
              <div class="flex h-12 w-12 items-center justify-center bg-primary/20 text-primary rounded-lg">
                <span class="material-symbols-outlined">category</span>
              </div>
            </div>
          </div>

          <!-- Total Users -->
          <div class="bg-surface rounded-lg border border-outline/20 p-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-label-caps text-label-caps text-on-surface-variant/60">Total de Usuários</p>
                <p class="font-headline-md text-headline-md text-primary">
                  <?php
                  $total_usuarios = contar_usuarios();
                  echo $total_usuarios;
                  ?>
                </p>
              </div>
              <div class="flex h-12 w-12 items-center justify-center bg-primary/20 text-primary rounded-lg">
                <span class="material-symbols-outlined">people</span>
              </div>
            </div>
          </div>

          <!-- Total Orders -->
          <div class="bg-surface rounded-lg border border-outline/20 p-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-label-caps text-label-caps text-on-surface-variant/60">Total de Pedidos</p>
                <p class="font-headline-md text-headline-md text-primary">
                  <?php
                  $total_pedidos = contar_pedidos();
                  echo $total_pedidos;
                  ?>
                </p>
              </div>
              <div class="flex h-12 w-12 items-center justify-center bg-primary/20 text-primary rounded-lg">
                <span class="material-symbols-outlined">list_alt</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-surface rounded-lg border border-outline/20 p-6">
          <div class="flex justify-between items-start mb-4">
            <h3 class="font-headline-sm text-headline-sm">Pedidos Recentes</h3>
            <a href="pedidos.php" class="text-sm text-primary hover:underline">Ver todos →</a>
          </div>
          <div class="space-y-4">
            <?php
            $pedidos_recentes = obter_pedidos(5, 0); // 5 pedidos mais recentes
            if (!empty($pedidos_recentes)):
              foreach ($pedidos_recentes as $pedido):
            ?>
              <div class="flex justify-between items-start py-3 border-b border-outline/10 last:border-b-0">
                <div class="flex-1 space-y-1">
                  <p class="font-body-sm text-body-sm">#<?php echo $pedido['id']; ?></p>
                  <p class="text-xs text-on-surface-variant/60">
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
                  <span class="font-headline-sm text-headline-sm ml-2">
                    <?php echo formatar_moeda($pedido['total']); ?>
                  </span>
                </div>
              </div>
            <?php
              endforeach;
            else:
            ?>
              <p class="text-center text-on-surface-variant/60 py-8">Nenhum pedido encontrado.</p>
            <?php
            endif;
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
