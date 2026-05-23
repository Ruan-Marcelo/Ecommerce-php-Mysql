<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: produtos.php');
    exit();
}

$produto = obter_produto_por_id($id);
if (!$produto) {
    header('Location: produtos.php');
    exit();
}

$titulo_pagina = $produto['nome'];
$semelhantes = [];
if (!empty($produto['categoria_id'])) {
    $semelhantes = array_values(array_filter(
        obter_produtos_por_categoria($produto['categoria_id'], 4, 0),
        fn($item) => (int) $item['id'] !== (int) $produto['id']
    ));
}

$imagem_principal = imagem_produto_disponivel($produto['imagem'] ?? '') ? imagem_produto_url($produto['imagem']) : '';
$galeria = array_filter([
    $imagem_principal,
    'public/assets/img/polo-piquet-premium-lupiere.jpg',
    'public/assets/img/logo.jpg',
]);

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="pt-32 pb-16 px-gutter">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
    <div class="space-y-4">
      <div class="bg-surface-container aspect-square overflow-hidden">
        <?php if ($imagem_principal): ?>
          <img src="<?php echo escapar($imagem_principal); ?>" alt="<?php echo escapar($produto['nome']); ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <div class="w-full h-full flex items-center justify-center">
            <span class="material-symbols-outlined text-on-surface-variant/60 text-6xl">inventory_2</span>
          </div>
        <?php endif; ?>
      </div>

      <div class="grid grid-cols-3 gap-4">
        <?php foreach ($galeria as $imagem): ?>
          <div class="bg-surface-container aspect-square overflow-hidden border border-outline/10">
            <img src="<?php echo escapar($imagem); ?>" alt="<?php echo escapar($produto['nome']); ?>" class="w-full h-full object-cover">
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="space-y-8">
      <div>
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">
          <?php echo escapar($produto['categoria_nome'] ?: 'Produto'); ?>
        </p>
        <h1 class="font-headline-lg text-headline-lg text-primary mb-6">
          <?php echo escapar($produto['nome']); ?>
        </h1>
        <p class="font-headline-md text-[32px] text-primary">
          <?php echo formatar_moeda($produto['preco']); ?>
        </p>
      </div>

      <div class="flex flex-wrap gap-3">
        <?php if ($produto['estoque'] > 0): ?>
          <span class="px-3 py-1 bg-green-500/20 text-green-700 text-xs uppercase tracking-widest">
            Em estoque: <?php echo (int) $produto['estoque']; ?> unidade(s)
          </span>
        <?php else: ?>
          <span class="px-3 py-1 bg-red-500/20 text-red-700 text-xs uppercase tracking-widest">Esgotado</span>
        <?php endif; ?>
      </div>

      <div class="prose max-w-none text-on-surface-variant font-body-lg text-body-lg">
        <?php echo nl2br(escapar($produto['descricao'])); ?>
      </div>

      <?php if (isset($_SESSION['usuario_id']) && $produto['estoque'] > 0): ?>
        <form action="adicionar_carrinho.php" method="post" class="space-y-5">
          <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
          <input type="hidden" name="redirect" value="produto.php?id=<?php echo $produto['id']; ?>">
          <div class="flex items-center gap-4">
            <label for="quantidade" class="font-label-caps text-label-caps">Quantidade</label>
            <input
              type="number"
              id="quantidade"
              name="quantidade"
              value="1"
              min="1"
              max="<?php echo (int) $produto['estoque']; ?>"
              class="w-24 form-input-bespoke py-3 text-primary"
            >
          </div>
          <button
            type="submit"
            class="w-full bg-primary-container text-white py-4 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
          >
            Adicionar ao carrinho
          </button>
        </form>
      <?php elseif (!isset($_SESSION['usuario_id'])): ?>
        <a
          href="login.php"
          class="block w-full border border-outline/30 text-primary py-4 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-surface-container-low transition-all duration-300 text-center"
        >
          Entrar para comprar
        </a>
      <?php else: ?>
        <p class="w-full text-center py-4 px-6 bg-red-500/20 text-red-700 font-label-caps">
          Produto indispon&iacute;vel
        </p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="py-16 px-gutter border-t border-outline/10">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-3 gap-10">
    <div>
      <h2 class="font-headline-md text-headline-md text-primary mb-4">Descri&ccedil;&atilde;o</h2>
      <p class="text-on-surface-variant"><?php echo nl2br(escapar($produto['descricao'])); ?></p>
    </div>
    <div>
      <h2 class="font-headline-md text-headline-md text-primary mb-4">Sobre o produto</h2>
      <p class="text-on-surface-variant">
        Pe&ccedil;a selecionada pela LUPI&Egrave;RE com foco em acabamento, caimento e presen&ccedil;a. Ideal para compor produ&ccedil;&otilde;es elegantes no dia a dia.
      </p>
    </div>
    <div>
      <h2 class="font-headline-md text-headline-md text-primary mb-4">Coment&aacute;rios</h2>
      <div class="space-y-4">
        <div class="border border-outline/20 p-4">
          <p class="text-primary font-semibold">Cliente LUPI&Egrave;RE</p>
          <p class="text-on-surface-variant text-sm">Acabamento excelente e entrega dentro do esperado.</p>
        </div>
        <div class="border border-outline/20 p-4">
          <p class="text-primary font-semibold">Compra verificada</p>
          <p class="text-on-surface-variant text-sm">Produto com boa apresenta&ccedil;&atilde;o e caimento sofisticado.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($semelhantes)): ?>
  <section class="py-16 px-gutter bg-surface-container-low">
    <div class="max-w-[1440px] mx-auto">
      <h2 class="font-headline-md text-headline-md text-primary mb-8">Produtos semelhantes</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($semelhantes as $similar): ?>
          <a href="produto.php?id=<?php echo $similar['id']; ?>" class="bg-surface border border-outline/20 overflow-hidden group">
            <div class="h-56 bg-surface-container overflow-hidden">
              <?php if (imagem_produto_disponivel($similar['imagem'] ?? '')): ?>
                <img src="<?php echo escapar(imagem_produto_url($similar['imagem'])); ?>" alt="<?php echo escapar($similar['nome']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                  <span class="material-symbols-outlined text-on-surface-variant/60">inventory_2</span>
                </div>
              <?php endif; ?>
            </div>
            <div class="p-4">
              <h3 class="font-headline-md text-[20px] text-primary"><?php echo escapar($similar['nome']); ?></h3>
              <p class="text-primary mt-2"><?php echo formatar_moeda($similar['preco']); ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
