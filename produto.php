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

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    validar_csrf();
    limitar_requisicoes('produto_' . $id, 20, 300);
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'comentar') {
        $nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : trim($_POST['nome'] ?? '');
        $comentario = trim($_POST['comentario'] ?? '');
        if ($nome !== '' && $comentario !== '') {
            adicionar_comentario_produto($id, $_SESSION['usuario_id'] ?? null, $nome, $comentario);
            $_SESSION['produto_sucesso'] = 'Comentário publicado.';
        } else {
            $_SESSION['produto_erro'] = 'Informe nome e comentário.';
        }
    }
    if ($acao === 'avaliar') {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['produto_erro'] = 'Entre na conta para avaliar.';
        } else {
            salvar_avaliacao_produto($id, $_SESSION['usuario_id'], (int) ($_POST['nota'] ?? 0));
            $_SESSION['produto_sucesso'] = 'Avaliação salva.';
        }
    }
    header('Location: produto.php?id=' . $id);
    exit();
}

$titulo_pagina = $produto['nome'];
$comentarios = obter_comentarios_produto($id);
$resumo_avaliacoes = obter_resumo_avaliacoes_produto($id);
$avaliacao_usuario = isset($_SESSION['usuario_id']) ? obter_avaliacao_usuario_produto($id, $_SESSION['usuario_id']) : 0;
$na_lista_desejos = isset($_SESSION['usuario_id']) ? produto_na_lista_desejos($_SESSION['usuario_id'], $id) : false;
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
        <div class="mt-4 flex items-center gap-3">
          <?php echo renderizar_estrelas($resumo_avaliacoes['media']); ?>
          <span class="text-sm text-on-surface-variant">
            <?php echo $resumo_avaliacoes['media']; ?> / 5 (<?php echo $resumo_avaliacoes['total']; ?>)
          </span>
        </div>
      </div>

      <?php
      if (isset($_SESSION['produto_sucesso'])) {
          echo mensagem_sucesso($_SESSION['produto_sucesso']);
          unset($_SESSION['produto_sucesso']);
      }
      if (isset($_SESSION['produto_erro'])) {
          echo mensagem_erro($_SESSION['produto_erro']);
          unset($_SESSION['produto_erro']);
      }
      ?>

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
          <?php echo csrf_input(); ?>
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

      <?php if (isset($_SESSION['usuario_id'])): ?>
        <form action="toggle_desejo.php" method="post">
          <?php echo csrf_input(); ?>
          <input type="hidden" name="produto_id" value="<?php echo (int) $produto['id']; ?>">
          <input type="hidden" name="redirect" value="produto.php?id=<?php echo (int) $produto['id']; ?>">
          <button type="submit" class="w-full border border-outline/30 text-primary py-4 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-surface-container-low transition-all duration-300">
            <?php echo $na_lista_desejos ? 'Remover da lista de desejos' : 'Adicionar à lista de desejos'; ?>
          </button>
        </form>
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
        <?php if (empty($comentarios)): ?>
          <p class="text-on-surface-variant text-sm">Nenhum comentário ainda.</p>
        <?php endif; ?>
        <?php foreach ($comentarios as $comentario): ?>
          <div class="border border-outline/20 p-4">
            <p class="text-primary font-semibold"><?php echo escapar($comentario['nome']); ?></p>
            <p class="text-on-surface-variant text-sm"><?php echo nl2br(escapar($comentario['comentario'])); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="py-16 px-gutter border-t border-outline/10">
  <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
    <div class="bg-surface border border-outline/20 rounded-lg p-6">
      <h2 class="font-headline-md text-headline-md text-primary mb-6">Comentar</h2>
      <form action="produto.php?id=<?php echo (int) $produto['id']; ?>" method="post" class="space-y-4">
        <?php echo csrf_input(); ?>
        <input type="hidden" name="acao" value="comentar">
        <?php if (!isset($_SESSION['usuario_id'])): ?>
          <input type="text" name="nome" placeholder="Seu nome" class="w-full form-input-bespoke py-3 text-primary" required>
        <?php endif; ?>
        <textarea name="comentario" rows="4" placeholder="Escreva seu comentário" class="w-full form-input-bespoke py-3 text-primary" required></textarea>
        <button type="submit" class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em]">Publicar comentário</button>
      </form>
    </div>

    <div class="bg-surface border border-outline/20 rounded-lg p-6">
      <h2 class="font-headline-md text-headline-md text-primary mb-6">Avaliar produto</h2>
      <?php if (!isset($_SESSION['usuario_id'])): ?>
        <a href="login.php" class="border border-outline/30 text-primary py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] inline-block">Entrar para avaliar</a>
      <?php else: ?>
        <form action="produto.php?id=<?php echo (int) $produto['id']; ?>" method="post" class="space-y-4">
          <?php echo csrf_input(); ?>
          <input type="hidden" name="acao" value="avaliar">
          <div class="flex gap-3 flex-wrap">
            <?php for ($nota = 1; $nota <= 5; $nota++): ?>
              <label class="cursor-pointer">
                <input type="radio" name="nota" value="<?php echo $nota; ?>" class="sr-only" <?php echo $avaliacao_usuario === $nota ? 'checked' : ''; ?> required>
                <span class="inline-flex items-center gap-1 border border-outline/20 px-3 py-2 hover:bg-surface-container">
                  <?php echo $nota; ?> <span class="material-symbols-outlined text-secondary text-[18px]">star</span>
                </span>
              </label>
            <?php endfor; ?>
          </div>
          <button type="submit" class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em]">Salvar avaliação</button>
        </form>
      <?php endif; ?>
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
