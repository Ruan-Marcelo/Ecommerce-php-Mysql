<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

$titulo_pagina = 'Acessórios';
$categoria_acessorios = garantir_categoria_acessorios();
$produtos = $categoria_acessorios ? obter_produtos_por_categoria((int) $categoria_acessorios['id']) : [];

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="pt-32 pb-24 px-gutter">
  <div class="max-w-[1440px] mx-auto w-full">
    <header class="mb-12 flex flex-col md:flex-row md:items-end md:justify-between gap-4 border-b border-secondary/20 pb-10">
      <div>
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">Curadoria</p>
        <h1 class="font-headline-lg text-headline-lg text-primary">Acess&oacute;rios</h1>
      </div>
      <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl">
        Pe&ccedil;as da categoria Acess&oacute;rios cadastradas no banco de dados.
      </p>
    </header>

    <?php if (empty($produtos)): ?>
      <div class="bg-surface rounded-lg border border-outline/20 p-10 text-center">
        <span class="material-symbols-outlined text-on-surface-variant/50 text-5xl mb-4">inventory_2</span>
        <p class="font-headline-md text-[24px] text-primary mb-2">Nenhum acess&oacute;rio cadastrado.</p>
        <p class="text-on-surface-variant">
          Cadastre produtos no admin e selecione a categoria Acess&oacute;rios para que apare&ccedil;am aqui.
        </p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($produtos as $produto): ?>
          <article class="bg-surface rounded-lg border border-outline/20 overflow-hidden flex flex-col min-h-full">
            <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="block bg-surface-container">
              <?php if (imagem_produto_disponivel($produto['imagem'] ?? '')): ?>
                <img
                  src="<?php echo escapar(imagem_produto_url($produto['imagem'])); ?>"
                  alt="<?php echo escapar($produto['nome']); ?>"
                  class="w-full h-[320px] object-cover"
                />
              <?php else: ?>
                <div class="w-full h-[320px] bg-surface-container flex items-center justify-center">
                  <span class="material-symbols-outlined text-on-surface-variant/60 text-5xl">inventory_2</span>
                </div>
              <?php endif; ?>
            </a>

            <div class="p-6 flex flex-col gap-5 flex-1">
              <div class="space-y-3">
                <h2 class="font-headline-md text-[24px] leading-tight text-primary">
                  <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="hover:text-secondary transition-colors">
                    <?php echo escapar($produto['nome']); ?>
                  </a>
                </h2>
                <p class="font-body-md text-body-md text-on-surface-variant line-clamp-3">
                  <?php echo escapar($produto['descricao']); ?>
                </p>
              </div>

              <div class="mt-auto flex items-center justify-between gap-4 text-primary">
                <span class="font-headline-md text-[24px]"><?php echo formatar_moeda($produto['preco']); ?></span>
                <?php if ((int) $produto['estoque'] > 0): ?>
                  <span class="text-green-600 font-label-caps text-[11px] uppercase">Em estoque</span>
                <?php else: ?>
                  <span class="text-red-600 font-label-caps text-[11px] uppercase">Esgotado</span>
                <?php endif; ?>
              </div>

              <div class="grid grid-cols-1 gap-3">
                <a
                  href="produto.php?id=<?php echo (int) $produto['id']; ?>"
                  class="border border-outline/30 text-primary py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-surface-container-low transition-all duration-300 text-center"
                >
                  Ver detalhes
                </a>

                <?php if (isset($_SESSION['usuario_id']) && (int) $produto['estoque'] > 0): ?>
                  <form action="adicionar_carrinho.php" method="post">
                    <input type="hidden" name="produto_id" value="<?php echo (int) $produto['id']; ?>">
                    <input type="hidden" name="redirect" value="acessorios.php">
                    <button
                      type="submit"
                      class="w-full bg-primary-container text-white py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                    >
                      Adicionar ao carrinho
                    </button>
                  </form>
                <?php elseif (!isset($_SESSION['usuario_id'])): ?>
                  <a
                    href="login.php"
                    class="bg-primary-container text-white py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300 text-center"
                  >
                    Entrar para comprar
                  </a>
                <?php else: ?>
                  <span class="text-center text-red-600 font-label-caps py-3">Indispon&iacute;vel</span>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
