<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

$titulo_pagina = 'InÃ­cio';
garantir_banner_padrao();
$banner = obter_banner_home();
$produtos_destaque = obter_produtos(6, 0);
$categorias_home = obter_categorias_com_capa();

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="relative min-h-[92vh] w-full overflow-hidden flex items-end">
  <div class="absolute inset-0 z-0 bg-primary">
    <?php if ($banner && !empty($banner['imagem'])): ?>
      <img
        class="w-full h-full object-cover opacity-85"
        src="<?php echo escapar(banner_imagem_url($banner['imagem'])); ?>"
        alt="<?php echo escapar($banner['titulo']); ?>"
      />
    <?php else: ?>
      <div class="w-full h-full bg-primary"></div>
    <?php endif; ?>
    <div class="absolute inset-0 bg-primary/35"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent"></div>
  </div>

  <div class="relative z-10 w-full px-6 md:px-16 pb-20 md:pb-28">
    <div class="max-w-[1440px] mx-auto">
      <div class="max-w-3xl">
        <p class="font-label-caps text-label-caps text-secondary-fixed-dim uppercase mb-5">Lupi&egrave;re Alfaiataria</p>
        <h1 class="font-headline-display text-[48px] md:text-[72px] leading-tight text-white mb-6">
          <?php echo escapar($banner['titulo'] ?? 'ElegÃ¢ncia com Personalidade'); ?>
        </h1>
        <p class="font-body-lg text-body-lg text-white/90 mb-10 border-l-2 border-secondary-fixed-dim pl-6 max-w-2xl">
          <?php echo escapar($banner['subtitulo'] ?? 'PeÃ§as feitas para quem impÃµe presenÃ§a.'); ?>
        </p>
        <a
          href="<?php echo escapar($banner['link_url'] ?? 'produtos.php'); ?>"
          class="inline-flex bg-primary-container text-white px-10 py-5 font-label-caps text-label-caps uppercase tracking-[0.2em] hover:bg-primary transition-all duration-300"
        >
          <?php echo escapar($banner['texto_botao'] ?? 'Explorar coleÃ§Ã£o'); ?>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="py-section-gap px-gutter">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-12 gap-10 items-center">
    <div class="md:col-span-5">
      <p class="font-label-caps text-label-caps text-secondary uppercase mb-6">Manifesto</p>
      <h2 class="font-headline-lg text-headline-lg text-primary">A essÃªncia</h2>
    </div>
    <div class="md:col-span-7 space-y-6 text-on-surface-variant">
      <p class="font-body-lg text-body-lg">
        A Lupi&egrave;re nasceu para resgatar o rigor da alfaiataria cl&aacute;ssica no ritmo do homem contempor&acirc;neo.
      </p>
      <p class="font-body-md text-body-md">
        Cada pe&ccedil;a combina presen&ccedil;a, corte preciso e materiais selecionados. O luxo aparece na constru&ccedil;&atilde;o, no caimento e na perman&ecirc;ncia.
      </p>
    </div>
  </div>
</section>

<section class="py-section-gap px-gutter">
  <div class="max-w-[1440px] mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
      <div>
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">Categorias</p>
        <h2 class="font-headline-lg text-headline-lg text-primary">Escolha por estilo</h2>
      </div>
      <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">
        Cada categoria leva direto para a listagem filtrada de produtos.
      </p>
    </div>

    <?php if (!empty($categorias_home)): ?>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php foreach ($categorias_home as $categoria): ?>
          <a href="produtos.php?categoria=<?php echo (int) $categoria['id']; ?>" class="group block bg-surface border border-outline/20 overflow-hidden rounded-lg">
            <div class="aspect-[4/3] bg-surface-container overflow-hidden">
              <?php if (imagem_produto_disponivel($categoria['imagem_capa'] ?? '')): ?>
                <img
                  src="<?php echo escapar(imagem_produto_url($categoria['imagem_capa'])); ?>"
                  alt="<?php echo escapar($categoria['nome']); ?>"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                />
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                  <span class="notranslate material-symbols-outlined text-on-surface-variant/60 text-5xl" translate="no">category</span>
                </div>
              <?php endif; ?>
            </div>
            <div class="p-6">
              <div class="flex items-start justify-between gap-4">
                <h3 class="font-headline-md text-[26px] text-primary group-hover:text-secondary transition-colors">
                  <?php echo escapar($categoria['nome']); ?>
                </h3>
                <span class="notranslate material-symbols-outlined text-primary/60 group-hover:text-secondary transition-colors" translate="no">arrow_forward</span>
              </div>
              <p class="mt-3 text-on-surface-variant line-clamp-2">
                <?php echo escapar($categoria['descricao'] ?: 'Ver produtos desta categoria.'); ?>
              </p>
              <p class="mt-5 font-label-caps text-label-caps text-secondary uppercase">
                <?php echo (int) $categoria['total_produtos']; ?> produtos
              </p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="py-section-gap px-gutter bg-surface-container-low">
  <div class="max-w-[1440px] mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
      <div>
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">Curadoria</p>
        <h2 class="font-headline-lg text-headline-lg text-primary">Produtos recentes</h2>
      </div>
      <a href="produtos.php" class="font-label-caps text-label-caps text-primary border-b border-primary/20 pb-1 hover:border-secondary hover:text-secondary transition-all self-start md:self-auto">Ver todos</a>
    </div>

    <?php if (empty($produtos_destaque)): ?>
      <p class="text-center text-on-surface-variant">Nenhum produto cadastrado.</p>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($produtos_destaque as $produto): ?>
          <?php $resumo = obter_resumo_avaliacoes_produto($produto['id']); ?>
          <article class="bg-surface rounded-lg border border-outline/20 overflow-hidden flex flex-col">
            <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="block bg-surface-container">
              <?php if (imagem_produto_disponivel($produto['imagem'] ?? '')): ?>
                <img
                  src="<?php echo escapar(imagem_produto_url($produto['imagem'])); ?>"
                  alt="<?php echo escapar($produto['nome']); ?>"
                  class="w-full h-[300px] object-cover"
                />
              <?php else: ?>
                <div class="w-full h-[300px] flex items-center justify-center bg-surface-container">
                  <span class="notranslate material-symbols-outlined text-on-surface-variant/60 text-5xl" translate="no">inventory_2</span>
                </div>
              <?php endif; ?>
            </a>
            <div class="p-6 flex flex-col gap-4 flex-1">
              <h3 class="font-headline-md text-[24px] leading-tight text-primary">
                <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="hover:text-secondary transition-colors">
                  <?php echo escapar($produto['nome']); ?>
                </a>
              </h3>
              <p class="text-on-surface-variant line-clamp-2"><?php echo escapar($produto['descricao']); ?></p>
              <div class="flex items-center gap-2"><?php echo renderizar_estrelas($resumo['media']); ?><span class="text-sm text-on-surface-variant"><?php echo $resumo['total']; ?></span></div>
              <div class="mt-auto flex items-center justify-between gap-4">
                <span class="font-headline-md text-[24px] text-primary"><?php echo formatar_moeda($produto['preco']); ?></span>
                <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="font-label-caps text-label-caps text-secondary uppercase">Detalhes</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="py-section-gap px-gutter bg-primary text-white">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
    <div>
      <p class="font-label-caps text-label-caps text-secondary-fixed-dim uppercase mb-6">HeranÃ§a</p>
      <h2 class="font-headline-lg text-headline-lg mb-8">O sÃ­mbolo da marca</h2>
      <p class="font-body-lg text-body-lg text-white/80">
        Uma identidade construÃ­da sobre precisÃ£o, postura e permanÃªncia. A roupa nÃ£o substitui presenÃ§a: ela a sustenta.
      </p>
    </div>
    <div class="bg-white/5 border border-white/10 p-10 flex items-center justify-center min-h-[320px]">
      <img src="public/assets/img/logo.jpg" alt="LupiÃ¨re" class="max-h-[260px] object-contain opacity-95">
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
