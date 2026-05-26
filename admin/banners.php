<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

$titulo_pagina = 'Gerenciar Banners';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $imagem_url = trim($_POST['imagem_url'] ?? '');
    $link_url = trim($_POST['link_url'] ?? 'produtos.php');
    $texto_botao = trim($_POST['texto_botao'] ?? 'Explorar coleÃ§Ã£o');
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $ordem = (int) ($_POST['ordem'] ?? 0);
    $imagem = $imagem_url;

    if (!empty($_FILES['imagem']['name'])) {
        $upload_result = upload_imagem($_FILES['imagem']);
        if (!empty($upload_result['erro'])) {
            $_SESSION['admin_erro'] = $upload_result['erro'];
        } else {
            $imagem = $upload_result['nome'];
        }
    }

    if (empty($_SESSION['admin_erro'])) {
        if ($action === 'excluir') {
            if ($id > 0 && excluir_banner($id)) {
                $_SESSION['admin_sucesso'] = 'Banner excluÃ­do com sucesso.';
            } else {
                $_SESSION['admin_erro'] = 'Erro ao excluir banner.';
            }
        } elseif ($titulo === '' || ($action === 'adicionar' && $imagem === '')) {
            $_SESSION['admin_erro'] = 'TÃ­tulo e imagem sÃ£o obrigatÃ³rios.';
        } elseif ($action === 'editar' && $id > 0) {
            if (atualizar_banner($id, $titulo, $subtitulo, $imagem, $link_url, $texto_botao, $ativo, $ordem)) {
                $_SESSION['admin_sucesso'] = 'Banner atualizado com sucesso.';
            } else {
                $_SESSION['admin_erro'] = 'Erro ao atualizar banner.';
            }
        } elseif ($action === 'adicionar') {
            if (adicionar_banner($titulo, $subtitulo, $imagem, $link_url, $texto_botao, $ativo, $ordem)) {
                $_SESSION['admin_sucesso'] = 'Banner cadastrado com sucesso.';
            } else {
                $_SESSION['admin_erro'] = 'Erro ao cadastrar banner.';
            }
        }
    }

    header('Location: banners.php');
    exit();
}

$banners = obter_banners_admin();
require_once dirname(__DIR__) . '/app/views/includes/head.php';
?>
<aside class="fixed top-0 left-0 h-full w-64 bg-primary text-on-primary z-40 flex flex-col">
  <div class="flex items-center justify-center py-8">
    <div class="text-xl font-headline-lg tracking-[0.4em] text-white">LUPI&Egrave;RE ADMIN</div>
  </div>
  <nav class="flex-1 flex-col pt-6 space-y-4">
    <a href="index.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">dashboard</span><span class="ml-3">Dashboard</span></a>
    <a href="produtos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">inventory_2</span><span class="ml-3">Produtos</span></a>
    <a href="categorias.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">category</span><span class="ml-3">Categorias</span></a>
    <a href="pedidos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">list_alt</span><span class="ml-3">Pedidos</span></a>
    <a href="banners.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">image</span><span class="ml-3">Banners</span></a>
    <a href="administradores.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">admin_panel_settings</span><span class="ml-3">Administradores</span></a>
    <a href="../logout.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors mt-auto"><span class="notranslate material-symbols-outlined" translate="no">logout</span><span class="ml-3">Sair</span></a>
  </nav>
</aside>

<main class="flex-grow ml-64 flex flex-col">
  <header class="fixed top-0 left-64 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-16 flex items-center">
    <div class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto">
      <div class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]">Banners</div>
    </div>
  </header>

  <div class="flex-grow py-section-gap px-gutter">
    <div class="max-w-container-max w-full mx-auto space-y-8">
      <?php
      if (isset($_SESSION['admin_sucesso'])) {
          echo mensagem_sucesso($_SESSION['admin_sucesso']);
          unset($_SESSION['admin_sucesso']);
      }
      if (isset($_SESSION['admin_erro'])) {
          echo mensagem_erro($_SESSION['admin_erro']);
          unset($_SESSION['admin_erro']);
      }
      ?>

      <section class="bg-surface rounded-lg border border-outline/20 p-6">
        <h2 class="font-headline-md text-headline-md mb-6">Cadastrar banner da home</h2>
        <form action="banners.php" method="POST" enctype="multipart/form-data" class="space-y-6">
          <input type="hidden" name="action" value="adicionar">
          <div class="grid gap-4 md:grid-cols-2">
            <div>
              <label class="block font-label-caps text-label-caps mb-2">TÃ­tulo</label>
              <input type="text" name="titulo" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" required>
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Link do botÃ£o</label>
              <input type="text" name="link_url" value="produtos.php" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Texto do botÃ£o</label>
              <input type="text" name="texto_botao" value="Explorar coleÃ§Ã£o" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Ordem</label>
              <input type="number" name="ordem" value="0" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Upload da imagem</label>
              <input type="file" name="imagem" accept="image/*" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Ou URL da imagem</label>
              <input type="text" name="imagem_url" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
            </div>
            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">SubtÃ­tulo</label>
              <textarea name="subtitulo" rows="3" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"></textarea>
            </div>
            <label class="flex items-center gap-3 text-primary">
              <input type="checkbox" name="ativo" checked>
              <span class="font-label-caps text-label-caps">Ativo</span>
            </label>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300">Salvar banner</button>
          </div>
        </form>
      </section>

      <section class="bg-surface rounded-lg border border-outline/20 p-6">
        <h2 class="font-headline-md text-headline-md mb-6">Banners cadastrados</h2>
        <div class="space-y-6">
          <?php if (empty($banners)): ?>
            <p class="text-on-surface-variant/60">Nenhum banner cadastrado.</p>
          <?php endif; ?>
          <?php foreach ($banners as $banner): ?>
            <form action="banners.php" method="POST" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-[180px_1fr] border border-outline/20 rounded-lg p-4">
              <input type="hidden" name="action" value="editar">
              <input type="hidden" name="id" value="<?php echo (int) $banner['id']; ?>">
              <div class="bg-surface-container h-40 overflow-hidden">
                <img src="<?php echo escapar(banner_imagem_url($banner['imagem'], '../')); ?>" alt="<?php echo escapar($banner['titulo']); ?>" class="w-full h-full object-cover">
              </div>
              <div class="grid gap-4 md:grid-cols-2">
                <input type="text" name="titulo" value="<?php echo escapar($banner['titulo']); ?>" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" required>
                <input type="text" name="link_url" value="<?php echo escapar($banner['link_url']); ?>" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
                <input type="text" name="texto_botao" value="<?php echo escapar($banner['texto_botao']); ?>" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
                <input type="number" name="ordem" value="<?php echo (int) $banner['ordem']; ?>" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
                <input type="file" name="imagem" accept="image/*" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
                <input type="text" name="imagem_url" value="<?php echo escapar($banner['imagem']); ?>" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
                <textarea name="subtitulo" rows="2" class="md:col-span-2 w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"><?php echo escapar($banner['subtitulo']); ?></textarea>
                <div class="md:col-span-2 flex justify-between items-center gap-4">
                  <label class="flex items-center gap-3 text-primary">
                    <input type="checkbox" name="ativo" <?php echo $banner['ativo'] ? 'checked' : ''; ?>>
                    <span class="font-label-caps text-label-caps">Ativo</span>
                  </label>
                  <div class="flex gap-3">
                    <button type="submit" class="bg-primary-container text-white py-2 px-4 font-label-caps text-label-caps tracking-[0.2em]">Atualizar</button>
                    <button type="submit" name="action" value="excluir" class="bg-red-500/20 text-red-600 py-2 px-4 font-label-caps text-label-caps tracking-[0.2em]" onclick="return confirm('Excluir este banner?')">Excluir</button>
                  </div>
                </div>
              </div>
            </form>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
  </div>
</main>

