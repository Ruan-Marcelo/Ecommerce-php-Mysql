<?php
$script_dir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$is_admin_area = basename($script_dir) === 'admin';
$base_path = $GLOBALS['lupiere_base_path'] ?? ($is_admin_area ? '../' : '');
$carrinho = $_SESSION['carrinho'] ?? [];
$total_carrinho = 0;
foreach ($carrinho as $item_carrinho) {
    $total_carrinho += ($item_carrinho['preco'] ?? 0) * ($item_carrinho['quantidade'] ?? 0);
}
$carrinho_aberto = isset($_GET['carrinho']) && $_GET['carrinho'] === 'aberto';
if (!empty($GLOBALS['lupiere_navbar_main_open'])):
?>
</main>
<?php endif; ?>

<?php if (!$is_admin_area): ?>
<?php $produtos_recomendados_footer = function_exists('obter_produtos_recomendados') ? obter_produtos_recomendados(4) : []; ?>
<?php if (!empty($produtos_recomendados_footer)): ?>
<section class="bg-surface-container-low py-16 px-gutter border-t border-[#1B3022]/10">
  <div class="max-w-[1440px] mx-auto">
    <div class="flex items-end justify-between gap-4 mb-8">
      <div>
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-3">Recomendações</p>
        <h2 class="font-headline-md text-headline-md text-primary">Você também pode gostar</h2>
      </div>
      <a href="<?php echo $base_path; ?>produtos.php" class="font-label-caps text-label-caps text-primary border-b border-primary/20 pb-1">Ver produtos</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($produtos_recomendados_footer as $produto_rec): ?>
        <?php $resumo_rec = obter_resumo_avaliacoes_produto($produto_rec['id']); ?>
        <a href="<?php echo $base_path; ?>produto.php?id=<?php echo (int) $produto_rec['id']; ?>" class="bg-surface border border-outline/20 overflow-hidden rounded-lg">
          <div class="h-44 bg-surface-container overflow-hidden">
            <?php if (imagem_produto_disponivel($produto_rec['imagem'] ?? '')): ?>
              <img src="<?php echo escapar(imagem_produto_url($produto_rec['imagem'], $base_path)); ?>" alt="<?php echo escapar($produto_rec['nome']); ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center">
                <span class="notranslate material-symbols-outlined text-on-surface-variant/60" translate="no">inventory_2</span>
              </div>
            <?php endif; ?>
          </div>
          <div class="p-4">
            <h3 class="font-headline-md text-[20px] text-primary line-clamp-1"><?php echo escapar($produto_rec['nome']); ?></h3>
            <div class="mt-2"><?php echo renderizar_estrelas($resumo_rec['media']); ?></div>
            <p class="mt-2 text-primary"><?php echo formatar_moeda($produto_rec['preco']); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php if (!isset($_COOKIE['lupiere_cookies_aceitos'])): ?>
<div
  id="cookieConsent"
  class="fixed bottom-0 left-0 right-0 z-[90] bg-primary text-white border-t border-white/10 px-6 py-4 shadow-2xl"
>
  <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
    <p class="text-sm md:text-base text-white/85 max-w-3xl">
      Usamos cookies para manter login, carrinho, prefer&ecirc;ncias e melhorar sua experi&ecirc;ncia na LUPI&Egrave;RE.
      Saiba mais em <a href="<?php echo $base_path; ?>cookies.php" class="underline hover:text-secondary-fixed-dim">Pol&iacute;tica de cookies</a>.
    </p>
    <div class="flex gap-3">
      <button
        type="button"
        id="rejectCookies"
        class="border border-white/30 px-4 py-3 font-label-caps text-label-caps tracking-[0.2em] text-white hover:bg-white/10 transition-all"
      >
        Recusar
      </button>
      <button
        type="button"
        id="acceptCookies"
        class="bg-white text-primary px-4 py-3 font-label-caps text-label-caps tracking-[0.2em] hover:bg-secondary-fixed transition-all"
      >
        Aceitar
      </button>
    </div>
  </div>
</div>
<?php endif; ?>
<div
  id="cartDrawerOverlay"
  class="fixed inset-0 bg-black/30 z-[60] transition-opacity <?php echo $carrinho_aberto ? '' : 'opacity-0 pointer-events-none'; ?>"
></div>
<aside
  id="cartDrawer"
  class="fixed top-0 right-0 z-[70] h-full w-full max-w-md bg-[#FAF9F4] shadow-2xl transition-transform duration-300 <?php echo $carrinho_aberto ? 'translate-x-0' : 'translate-x-full'; ?> flex flex-col"
>
  <div class="h-20 px-6 border-b border-[#1B3022]/10 flex items-center justify-between">
    <div>
      <p class="font-label-caps text-[11px] tracking-[0.2em] uppercase text-[#1B3022]/50">Carrinho</p>
      <h3 class="font-headline-md text-[24px] text-[#1B3022]">Itens adicionados</h3>
    </div>
    <button type="button" id="closeCartDrawer" class="text-[#1B3022]">
      <span class="notranslate material-symbols-outlined" translate="no">close</span>
    </button>
  </div>

  <div class="flex-1 overflow-y-auto p-6 space-y-4">
    <?php if (empty($carrinho)): ?>
      <p class="text-[#1B3022]/60">Seu carrinho est&aacute; vazio.</p>
    <?php else: ?>
      <?php foreach ($carrinho as $item_carrinho): ?>
        <div class="flex gap-4 border-b border-[#1B3022]/10 pb-4">
          <div class="w-20 h-20 bg-surface-container flex items-center justify-center flex-shrink-0">
            <?php if (function_exists('imagem_produto_disponivel') && imagem_produto_disponivel($item_carrinho['imagem'] ?? '')): ?>
              <img
                src="<?php echo escapar(imagem_produto_url($item_carrinho['imagem'])); ?>"
                alt="<?php echo escapar($item_carrinho['nome'] ?? 'Produto'); ?>"
                class="w-full h-full object-cover"
              >
            <?php else: ?>
              <span class="notranslate material-symbols-outlined text-[#1B3022]/40" translate="no">inventory_2</span>
            <?php endif; ?>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-body-md text-[#1B3022] line-clamp-2"><?php echo escapar($item_carrinho['nome'] ?? 'Produto'); ?></p>
            <p class="text-sm text-[#1B3022]/60">
              <?php echo (int) ($item_carrinho['quantidade'] ?? 0); ?>x <?php echo formatar_moeda($item_carrinho['preco'] ?? 0); ?>
            </p>
          </div>
          <div class="text-right text-sm text-[#1B3022]">
            <?php echo formatar_moeda(($item_carrinho['preco'] ?? 0) * ($item_carrinho['quantidade'] ?? 0)); ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="p-6 border-t border-[#1B3022]/10 space-y-4">
    <div class="flex items-center justify-between text-[#1B3022]">
      <span class="font-label-caps text-label-caps">Total</span>
      <strong class="font-headline-md text-[24px]"><?php echo formatar_moeda($total_carrinho); ?></strong>
    </div>
    <a
      href="<?php echo $base_path; ?>carrinho.php"
      class="block w-full bg-primary-container text-white py-4 px-6 font-label-caps text-label-caps tracking-[0.2em] text-center hover:bg-primary transition-all duration-300"
    >
      Ir para o carrinho
    </a>
    <button
      type="button"
      id="continueShopping"
      class="w-full border border-[#1B3022]/20 text-[#1B3022] py-4 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-[#1B3022]/5 transition-all duration-300"
    >
      Continuar comprando
    </button>
  </div>
</aside>
<?php endif; ?>

<!-- Footer -->
<footer
  class="bg-[#FAF9F4] w-full py-24 px-8 md:px-16 border-t border-[#1B3022]/10"
>
  <div
    class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12"
  >
    <div class="col-span-1 md:col-span-1">
      <div
        class="text-2xl font-headline-lg tracking-[0.3em] text-[#1B3022] uppercase mb-8"
      >
        LUPI&Egrave;RE
      </div>
      <p class="font-body-md text-[#1B3022]/60 max-w-xs">
        Elevando a alfaiataria cl&aacute;ssica para o homem contempor&acirc;neo. Rigor,
        tradi&ccedil;&atilde;o e personalidade.
      </p>
    </div>
    <div class="flex flex-col gap-4">
      <h4
        class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
      >
        Explorar
      </h4>
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>index.php"
        >In&iacute;cio</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>produtos.php"
        >Cole&ccedil;&otilde;es</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>acessorios.php"
        >Acess&oacute;rios</a
      >
    </div>
    <div class="flex flex-col gap-4">
      <h4
        class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
      >
        Atendimento ao Cliente
      </h4>
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>frete-envio.php"
        >Envio &amp; Devolu&ccedil;&otilde;es</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>privacidade.php"
        >Pol&iacute;tica de Privacidade</a
      >
    </div>
    <div class="flex flex-col gap-4">
      <h4
        class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
      >
        Atelier
      </h4>
      <p class="font-body-md text-[#1B3022]/60">
        Avenida da Liberdade, 110<br />
        1250-146 Lisboa, Portugal
      </p>
      <div class="mt-4 flex gap-6">
        <a
          class="text-[#1B3022]/60 hover:text-[#1B3022]"
          href="https://www.instagram.com/uselupiere/"
          target="_blank"
          rel="noopener noreferrer"
        >
          <span class="notranslate material-symbols-outlined text-[20px]" translate="no">
            photo_camera
          </span>
        </a>
        <a class="text-[#1B3022]/60 hover:text-[#1B3022]" href="mailto:info@lupiere.com" target="_blank" rel="noopener noreferrer">
          <span class="notranslate material-symbols-outlined text-[20px]" translate="no">mail</span>
        </a>
      </div>
    </div>
  </div>
  <div
    class="max-w-[1440px] mx-auto mt-24 pt-12 border-t border-[#1B3022]/5 flex flex-col md:flex-row justify-between items-center gap-6"
  >
    <div
      class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase"
    >
      &copy; <?php echo date('Y'); ?> LUPI&Egrave;RE ALFAIATARIA. TODOS OS DIREITOS RESERVADOS.
    </div>
    <div class="flex gap-8">
      <a
        class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>termos.php"
        >Termos</a
      >
      <a
        class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>cookies.php"
        >Cookies</a
      >
    </div>
  </div>
</footer>

<script src="<?php echo $base_path; ?>public/assets/js/main.js"></script>
<script>
  (function () {
    const drawer = document.getElementById('cartDrawer');
    const overlay = document.getElementById('cartDrawerOverlay');
    const closeBtn = document.getElementById('closeCartDrawer');
    const continueBtn = document.getElementById('continueShopping');

    function closeDrawer() {
      if (!drawer || !overlay) return;
      drawer.classList.add('translate-x-full');
      drawer.classList.remove('translate-x-0');
      overlay.classList.add('opacity-0', 'pointer-events-none');
      const url = new URL(window.location.href);
      url.searchParams.delete('carrinho');
      window.history.replaceState({}, '', url);
    }

    closeBtn?.addEventListener('click', closeDrawer);
    continueBtn?.addEventListener('click', closeDrawer);
    overlay?.addEventListener('click', closeDrawer);
  })();
</script>
<?php if (!$is_admin_area && !isset($_COOKIE['lupiere_cookies_aceitos'])): ?>
<script>
  (function () {
    const banner = document.getElementById('cookieConsent');
    const maxAge = 60 * 60 * 24 * 365;

    function salvarConsentimento(valor) {
      document.cookie = 'lupiere_cookies_aceitos=' + valor + '; max-age=' + maxAge + '; path=/; SameSite=Lax';
      banner?.remove();
    }

    document.getElementById('acceptCookies')?.addEventListener('click', function () {
      salvarConsentimento('1');
    });

    document.getElementById('rejectCookies')?.addEventListener('click', function () {
      salvarConsentimento('0');
    });
  })();
</script>
<?php endif; ?>
</body>
</html>
