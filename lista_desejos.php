<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';
proteger_pagina();

$titulo_pagina = 'Lista de desejos';
$produtos = obter_lista_desejos_usuario($_SESSION['usuario_id']);

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="pt-32 pb-24 px-gutter">
  <div class="max-w-[1440px] mx-auto">
    <header class="mb-12">
      <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">Sua seleção</p>
      <h1 class="font-headline-lg text-headline-lg text-primary">Lista de desejos</h1>
    </header>

    <?php if (empty($produtos)): ?>
      <div class="border border-outline/20 rounded-lg p-10 text-center bg-surface">
        <span class="notranslate material-symbols-outlined text-5xl text-on-surface-variant/60 mb-4" translate="no">favorite</span>
        <p class="text-on-surface-variant">Nenhum produto salvo ainda.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($produtos as $produto): ?>
          <?php $resumo = obter_resumo_avaliacoes_produto($produto['id']); ?>
          <article class="bg-surface rounded-lg border border-outline/20 overflow-hidden flex flex-col">
            <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="block bg-surface-container">
              <?php if (imagem_produto_disponivel($produto['imagem'] ?? '')): ?>
                <img src="<?php echo escapar(imagem_produto_url($produto['imagem'])); ?>" alt="<?php echo escapar($produto['nome']); ?>" class="w-full h-[300px] object-cover">
              <?php else: ?>
                <div class="w-full h-[300px] flex items-center justify-center bg-surface-container">
                  <span class="notranslate material-symbols-outlined text-on-surface-variant/60 text-5xl" translate="no">inventory_2</span>
                </div>
              <?php endif; ?>
            </a>
            <div class="p-6 flex flex-col gap-4 flex-1">
              <h2 class="font-headline-md text-[24px] text-primary"><?php echo escapar($produto['nome']); ?></h2>
              <div class="flex items-center gap-2"><?php echo renderizar_estrelas($resumo['media']); ?><span class="text-sm text-on-surface-variant"><?php echo $resumo['total']; ?></span></div>
              <p class="font-headline-md text-[24px] text-primary"><?php echo formatar_moeda($produto['preco']); ?></p>
              <div class="grid gap-3 mt-auto">
                <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="border border-outline/30 text-primary py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] text-center">Ver detalhes</a>
                <form action="toggle_desejo.php" method="post">
                  <?php echo csrf_input(); ?>
                  <input type="hidden" name="produto_id" value="<?php echo (int) $produto['id']; ?>">
                  <input type="hidden" name="redirect" value="lista_desejos.php">
                  <button type="submit" class="w-full bg-red-500/20 text-red-600 py-3 px-4 font-label-caps text-label-caps tracking-[0.2em]">Remover</button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
