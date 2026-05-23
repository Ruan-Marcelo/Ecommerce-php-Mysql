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
      <span class="material-symbols-outlined">close</span>
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
              <span class="material-symbols-outlined text-[#1B3022]/40">inventory_2</span>
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
        href="<?php echo $base_path; ?>contato.php"
        >Envio &amp; Devolu&ccedil;&otilde;es</a
      >
      <a
        class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>contato.php"
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
          <span class="material-symbols-outlined text-[20px]">
            photo_camera
          </span>
        </a>
        <a class="text-[#1B3022]/60 hover:text-[#1B3022]" href="mailto:info@lupiere.com" target="_blank" rel="noopener noreferrer">
          <span class="material-symbols-outlined text-[20px]">mail</span>
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
        href="<?php echo $base_path; ?>contato.php"
        >Termos</a
      >
      <a
        class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
        href="<?php echo $base_path; ?>contato.php"
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
</body>
</html>
