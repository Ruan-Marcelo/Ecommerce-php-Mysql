<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

$titulo_pagina = 'Gerenciar Administradores';
global $pdo;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha_novo_admin = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $senha_admin_atual = $_POST['senha_admin_atual'] ?? '';
    $admin_atual = obter_usuario_por_id($_SESSION['usuario_id']);

    if (!$admin_atual || !password_verify($senha_admin_atual, $admin_atual['senha'])) {
        $_SESSION['admin_erro'] = 'Senha do administrador logado incorreta.';
    } elseif ($nome === '' || $email === '' || $senha_novo_admin === '') {
        $_SESSION['admin_erro'] = 'Nome, e-mail e senha do novo administrador são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['admin_erro'] = 'Informe um e-mail válido.';
    } elseif ($senha_novo_admin !== $confirmar_senha) {
        $_SESSION['admin_erro'] = 'A senha e a confirmação do novo administrador não coincidem.';
    } elseif (email_existe($email)) {
        $_SESSION['admin_erro'] = 'Este e-mail já está cadastrado.';
    } elseif (criar_usuario($nome, $email, $senha_novo_admin, $telefone, 1)) {
        $_SESSION['admin_sucesso'] = 'Administrador cadastrado com sucesso.';
    } else {
        $_SESSION['admin_erro'] = 'Erro ao cadastrar administrador.';
    }

    header('Location: administradores.php');
    exit();
}

$stmt = $pdo->query("SELECT id, nome, email, telefone, data_criacao FROM usuarios WHERE admin = 1 ORDER BY data_criacao DESC");
$administradores = $stmt->fetchAll();

require_once dirname(__DIR__) . '/app/views/includes/head.php';
?>
<!-- Admin Sidebar -->
<aside class="fixed top-0 left-0 h-full w-64 bg-primary text-on-primary z-40 flex flex-col">
  <div class="flex items-center justify-center py-8">
    <div class="text-xl font-headline-lg tracking-[0.4em] text-white">LUPI&Egrave;RE ADMIN</div>
  </div>
  <nav class="flex-1 flex-col pt-6 space-y-4">
    <a href="index.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors">
      <span class="material-symbols-outlined">dashboard</span>
      <span class="ml-3">Dashboard</span>
    </a>
    <a href="produtos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors">
      <span class="material-symbols-outlined">inventory_2</span>
      <span class="ml-3">Produtos</span>
    </a>
    <a href="categorias.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors">
      <span class="material-symbols-outlined">category</span>
      <span class="ml-3">Categorias</span>
    </a>
    <a href="pedidos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors">
      <span class="material-symbols-outlined">list_alt</span>
      <span class="ml-3">Pedidos</span>
    </a>
    <a href="banners.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors">
      <span class="material-symbols-outlined">image</span>
      <span class="ml-3">Banners</span>
    </a>
    <a href="administradores.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors">
      <span class="material-symbols-outlined">admin_panel_settings</span>
      <span class="ml-3">Administradores</span>
    </a>
    <a href="../logout.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors mt-auto">
      <span class="material-symbols-outlined">logout</span>
      <span class="ml-3">Sair</span>
    </a>
  </nav>
</aside>

<main class="flex-grow ml-64 flex flex-col">
  <header class="fixed top-0 left-64 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-16 flex items-center">
    <div class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto">
      <div class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]">Administradores</div>
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
        <h2 class="font-headline-md text-headline-md mb-6">Cadastrar novo administrador</h2>
        <form action="administradores.php" method="POST" class="space-y-6">
          <div class="grid gap-4 md:grid-cols-2">
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Nome</label>
              <input type="text" name="nome" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" required>
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">E-mail</label>
              <input type="email" name="email" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" required>
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Telefone</label>
              <input type="tel" name="telefone" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Senha do administrador logado</label>
              <input type="password" name="senha_admin_atual" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" autocomplete="current-password" required>
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Senha do novo admin</label>
              <input type="password" name="senha" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" autocomplete="new-password" required>
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Confirmar senha</label>
              <input type="password" name="confirmar_senha" class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary" autocomplete="new-password" required>
            </div>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300">
              Cadastrar administrador
            </button>
          </div>
        </form>
      </section>

      <section class="bg-surface rounded-lg border border-outline/20 p-6">
        <h2 class="font-headline-md text-headline-md mb-6">Administradores cadastrados</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-outline/20">
            <thead class="bg-primary/10">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">E-mail</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Telefone</th>
                <th class="px-6 py-3 text-left text-xs font-label-caps text-label-caps tracking-[0.2em] text-on-surface-variant/60">Data</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-outline/20">
              <?php foreach ($administradores as $admin): ?>
                <tr>
                  <td class="px-6 py-4 text-font-body-md text-body-md"><?php echo escapar($admin['nome']); ?></td>
                  <td class="px-6 py-4 text-font-body-md text-body-md"><?php echo escapar($admin['email']); ?></td>
                  <td class="px-6 py-4 text-font-body-md text-body-md"><?php echo escapar($admin['telefone'] ?: '-'); ?></td>
                  <td class="px-6 py-4 text-font-body-md text-body-md">
                    <?php echo (new DateTime($admin['data_criacao']))->format('d/m/Y'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</main>

