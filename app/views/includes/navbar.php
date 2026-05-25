<?php
$script_dir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$base_path = basename($script_dir) === 'admin' ? '../' : '';
$conta_url = isset($_SESSION['usuario_id']) ? (!empty($_SESSION['admin']) ? 'admin/index.php' : 'perfil.php') : 'login.php';
$GLOBALS['lupiere_base_path'] = $base_path;
$GLOBALS['lupiere_navbar_main_open'] = true;
?>
<!-- TopNavBar -->
<header
  class="fixed top-0 left-0 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-20 flex items-center transition-all duration-300"
>
  <div
    class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto"
  >
    <!-- BOTAO MENU MOBILE -->
    <button id="menuBtn" class="lg:hidden text-[#1B3022]">
      <span class="material-symbols-outlined">menu</span>
    </button>

    <!-- NAV DESKTOP -->
    <nav class="hidden lg:flex gap-10">
      <a class="nav-link" href="<?php echo $base_path; ?>index.php">In&iacute;cio</a>
      <a class="nav-link" href="<?php echo $base_path; ?>produtos.php">Cole&ccedil;&otilde;es</a>
      <a class="nav-link" href="<?php echo $base_path; ?>acessorios.php">Acess&oacute;rios</a>
      <a class="nav-link" href="<?php echo $base_path; ?>sobre.php">Nossa hist&oacute;ria</a>
    </nav>

    <!-- LOGO -->
    <div
      class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]"
    >
      LUPI&Egrave;RE
    </div>

    <!-- ICONES -->
    <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
      <a href="<?php echo $base_path; ?>carrinho.php" class="icon-btn">
        <span class="material-symbols-outlined">shopping_bag</span>
      </a>
      <a href="<?php echo $base_path . $conta_url; ?>" class="icon-btn">
        <span class="material-symbols-outlined">person</span>
      </a>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="<?php echo $base_path; ?>lista_desejos.php" class="icon-btn" aria-label="Lista de desejos">
          <span class="material-symbols-outlined">favorite</span>
        </a>
      <?php endif; ?>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="<?php echo $base_path; ?>logout.php" class="icon-btn" aria-label="Sair da conta">
          <span class="material-symbols-outlined">logout</span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- MENU MOBILE -->
<div
  id="mobileMenu"
  class="fixed top-0 left-[-100%] w-72 h-full bg-[#FAF9F4] z-50 transition-all duration-300 shadow-xl p-8 flex flex-col gap-8"
>
  <div class="flex justify-between items-center">
    <span class="font-label-caps text-sm tracking-widest">MENU</span>
    <button id="closeMenu">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>

  <a class="nav-link" href="<?php echo $base_path; ?>index.php">In&iacute;cio</a>
  <a class="nav-link" href="<?php echo $base_path; ?>produtos.php">Cole&ccedil;&otilde;es</a>
  <a class="nav-link" href="<?php echo $base_path; ?>acessorios.php">Acess&oacute;rios</a>
  <a class="nav-link" href="<?php echo $base_path; ?>sobre.php">Nossa hist&oacute;ria</a>
  <?php if (isset($_SESSION['usuario_id'])): ?>
    <a class="nav-link" href="<?php echo $base_path; ?>lista_desejos.php">Desejos</a>
  <?php endif; ?>
  <?php if (isset($_SESSION['usuario_id'])): ?>
    <a class="nav-link" href="<?php echo $base_path; ?>logout.php">Sair</a>
  <?php endif; ?>
</div>

<!-- OVERLAY -->
<div
  id="overlay"
  class="fixed inset-0 bg-black/30 opacity-0 pointer-events-none transition-all duration-300 z-40"
></div>

<main class="flex-grow">
