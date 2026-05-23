<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';
proteger_pagina();

$usuario_id = (int) $_SESSION['usuario_id'];
$usuario = obter_usuario_por_id($usuario_id);

if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($nome === '' || $email === '') {
        $erro = 'Nome e e-mail s&atilde;o obrigat&oacute;rios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail v&aacute;lido.';
    } elseif (email_existe($email, $usuario_id)) {
        $erro = 'Este e-mail j&aacute; est&aacute; em uso.';
    } elseif (($nova_senha !== '' || $confirmar_senha !== '') && !password_verify($senha_atual, $usuario['senha'])) {
        $erro = 'Senha atual incorreta.';
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = 'Nova senha e confirma&ccedil;&atilde;o devem coincidir.';
    } else {
        $senha_para_salvar = $nova_senha !== '' ? $nova_senha : null;
        if (atualizar_usuario($usuario_id, $nome, $email, $telefone, $senha_para_salvar)) {
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            $sucesso = 'Perfil atualizado com sucesso.';
            $usuario = obter_usuario_por_id($usuario_id);
        } else {
            $erro = 'Erro ao atualizar perfil.';
        }
    }
}

$pedidos = obter_pedidos_usuario($usuario_id, 3, 0);
$titulo_pagina = 'Meu perfil';

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="pt-32 pb-24 px-gutter">
  <div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-8">
    <aside class="space-y-6">
      <div class="bg-surface border border-outline/20 p-6">
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-3">Conta</p>
        <h1 class="font-headline-md text-headline-md text-primary mb-2">
          <?php echo escapar($usuario['nome']); ?>
        </h1>
        <p class="text-on-surface-variant"><?php echo escapar($usuario['email']); ?></p>
      </div>

      <?php if (!empty($_SESSION['admin'])): ?>
        <a
          href="admin/index.php"
          class="block bg-primary-container text-white p-6 hover:bg-primary transition-colors"
        >
          <span class="material-symbols-outlined mb-4">admin_panel_settings</span>
          <h2 class="font-headline-md text-[24px] mb-2">Painel administrativo</h2>
          <p class="text-white/75">Acessar dashboard, produtos, categorias e pedidos.</p>
        </a>
      <?php endif; ?>

      <a href="historico.php" class="block border border-outline/20 p-6 text-primary hover:bg-surface-container-low transition-colors">
        <span class="material-symbols-outlined mb-4">receipt_long</span>
        <p class="font-label-caps text-label-caps">Hist&oacute;rico de pedidos</p>
      </a>
    </aside>

    <div class="space-y-8">
      <?php if (isset($sucesso)): ?>
        <?php echo mensagem_sucesso($sucesso); ?>
      <?php endif; ?>
      <?php if (isset($erro)): ?>
        <?php echo mensagem_erro($erro); ?>
      <?php endif; ?>

      <div class="bg-surface border border-outline/20 p-6 md:p-8">
        <h2 class="font-headline-md text-headline-md text-primary mb-8">Dados do perfil</h2>
        <form action="perfil.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block font-label-caps text-label-caps mb-2">Nome</label>
            <input type="text" name="nome" value="<?php echo escapar($usuario['nome']); ?>" class="w-full form-input-bespoke py-3 text-primary" required>
          </div>
          <div>
            <label class="block font-label-caps text-label-caps mb-2">E-mail</label>
            <input type="email" name="email" value="<?php echo escapar($usuario['email']); ?>" class="w-full form-input-bespoke py-3 text-primary" required>
          </div>
          <div>
            <label class="block font-label-caps text-label-caps mb-2">Telefone</label>
            <input type="tel" name="telefone" value="<?php echo escapar($usuario['telefone'] ?? ''); ?>" class="w-full form-input-bespoke py-3 text-primary">
          </div>
          <div>
            <label class="block font-label-caps text-label-caps mb-2">Senha atual</label>
            <input type="password" name="senha_atual" class="w-full form-input-bespoke py-3 text-primary" autocomplete="current-password">
          </div>
          <div>
            <label class="block font-label-caps text-label-caps mb-2">Nova senha</label>
            <input type="password" name="nova_senha" class="w-full form-input-bespoke py-3 text-primary" autocomplete="new-password">
          </div>
          <div>
            <label class="block font-label-caps text-label-caps mb-2">Confirmar nova senha</label>
            <input type="password" name="confirmar_senha" class="w-full form-input-bespoke py-3 text-primary" autocomplete="new-password">
          </div>
          <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="bg-primary-container text-white py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300">
              Salvar altera&ccedil;&otilde;es
            </button>
          </div>
        </form>
      </div>

      <div class="bg-surface border border-outline/20 p-6 md:p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
          <h2 class="font-headline-md text-headline-md text-primary">Pedidos recentes</h2>
          <a href="historico.php" class="font-label-caps text-label-caps text-primary hover:text-secondary">Ver todos</a>
        </div>
        <?php if (empty($pedidos)): ?>
          <p class="text-on-surface-variant">Nenhum pedido encontrado.</p>
        <?php else: ?>
          <div class="space-y-3">
            <?php foreach ($pedidos as $pedido): ?>
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border border-outline/20 p-4">
                <div>
                  <p class="font-label-caps text-label-caps text-primary">Pedido #<?php echo (int) $pedido['id']; ?></p>
                  <p class="text-on-surface-variant text-sm"><?php echo escapar($pedido['status']); ?></p>
                </div>
                <p class="font-headline-md text-[22px] text-primary"><?php echo formatar_moeda($pedido['total']); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
