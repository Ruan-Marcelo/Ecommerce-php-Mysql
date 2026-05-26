<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

$titulo_pagina = 'Servicos de Email';
global $pdo;
criar_tabelas_email_se_necessario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'inscrever') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if (garantir_inscricao_email($email, $nome, null, 'admin', 1)) {
            $_SESSION['admin_sucesso'] = 'Contato inscrito com sucesso.';
        } else {
            $_SESSION['admin_erro'] = 'Informe um e-mail valido.';
        }
        header('Location: emails.php');
        exit();
    }

    if ($acao === 'campanha') {
        $titulo = trim($_POST['titulo'] ?? '');
        $assunto = trim($_POST['assunto'] ?? '');
        $tipo = $_POST['tipo'] ?? 'promocao';
        $publico = $_POST['publico'] ?? 'inscritos';
        $conteudo = trim($_POST['conteudo_html'] ?? '');
        $selecionados = $_POST['usuarios'] ?? [];

        if ($titulo === '' || $assunto === '' || $conteudo === '') {
            $_SESSION['admin_erro'] = 'Titulo, assunto e conteudo sao obrigatorios.';
        } else {
            $campanha_id = criar_campanha_email($titulo, $assunto, $tipo, $publico, $conteudo, $_SESSION['usuario_id'] ?? null);
            $total = enfileirar_campanha_email($campanha_id, $publico, $selecionados);
            $_SESSION['admin_sucesso'] = "Campanha criada e {$total} e-mail(s) adicionados na fila.";
        }
        header('Location: emails.php');
        exit();
    }

    if ($acao === 'automacao') {
        $nome = trim($_POST['nome'] ?? '');
        $tipo = $_POST['tipo'] ?? 'promocao';
        $assunto = trim($_POST['assunto'] ?? '');
        $conteudo = trim($_POST['conteudo_html'] ?? '');
        $intervalo = (int) ($_POST['intervalo_minutos'] ?? 1440);
        if ($nome === '' || $assunto === '' || $conteudo === '') {
            $_SESSION['admin_erro'] = 'Nome, assunto e conteudo da automacao sao obrigatorios.';
        } else {
            salvar_automacao_email($nome, $tipo, $assunto, $conteudo, $intervalo, isset($_POST['ativo']));
            $_SESSION['admin_sucesso'] = 'Automacao cadastrada com sucesso.';
        }
        header('Location: emails.php');
        exit();
    }

    if ($acao === 'processar') {
        $automacoes = processar_automacoes_email();
        $fila = processar_fila_emails(30);
        $_SESSION['admin_sucesso'] = "Automacoes geraram {$automacoes} e-mail(s). Fila: {$fila['enviados']} enviado(s), {$fila['falhas']} falha(s).";
        header('Location: emails.php');
        exit();
    }

    if ($acao === 'config_smtp') {
        $host = trim($_POST['host'] ?? '');
        $porta = (int) ($_POST['porta'] ?? 587);
        $criptografia = $_POST['criptografia'] ?? 'tls';
        $usuario = trim($_POST['usuario'] ?? '');
        $senha = (string) ($_POST['senha'] ?? '');
        $remetente_email = trim($_POST['remetente_email'] ?? '');
        $remetente_nome = trim($_POST['remetente_nome'] ?? 'LUPIERE');
        $ativo = isset($_POST['ativo']);

        if ($ativo && ($host === '' || $remetente_email === '')) {
            $_SESSION['admin_erro'] = 'Host SMTP e e-mail remetente sao obrigatorios para ativar o SMTP.';
        } elseif (salvar_config_email($host, $porta, $criptografia, $usuario, $senha, $remetente_email, $remetente_nome, $ativo)) {
            $_SESSION['admin_sucesso'] = 'Configuracao SMTP salva.';
        } else {
            $_SESSION['admin_erro'] = 'Erro ao salvar configuracao SMTP.';
        }
        header('Location: emails.php');
        exit();
    }

    if ($acao === 'teste_smtp') {
        $email_teste = trim($_POST['email_teste'] ?? '');
        if (!filter_var($email_teste, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['admin_erro'] = 'Informe um e-mail de teste valido.';
        } else {
            enfileirar_email($email_teste, 'Teste', 'Teste de envio LUPIERE', '<p>Se voce recebeu este e-mail, o SMTP da LUPIERE esta configurado.</p>');
            $resultado = processar_fila_emails(1);
            if ($resultado['enviados'] > 0) {
                $_SESSION['admin_sucesso'] = 'E-mail de teste enviado.';
            } else {
                $_SESSION['admin_erro'] = 'Teste enfileirado, mas o envio falhou. Veja o erro em Fila e historico.';
            }
        }
        header('Location: emails.php');
        exit();
    }
}

$config_email = obter_config_email();
$usuarios = $pdo->query("SELECT id, nome, email FROM usuarios WHERE admin = 0 ORDER BY nome")->fetchAll();
$inscritos = $pdo->query("SELECT * FROM email_inscritos ORDER BY data_criacao DESC LIMIT 20")->fetchAll();
$campanhas = $pdo->query("SELECT * FROM email_campanhas ORDER BY data_criacao DESC LIMIT 10")->fetchAll();
$fila = $pdo->query("SELECT * FROM email_fila ORDER BY data_criacao DESC LIMIT 20")->fetchAll();
$automacoes = obter_automacoes_email();
$stats = [
    'inscritos' => (int) $pdo->query("SELECT COUNT(*) FROM email_inscritos WHERE ativo = 1")->fetchColumn(),
    'pendentes' => (int) $pdo->query("SELECT COUNT(*) FROM email_fila WHERE status = 'pendente'")->fetchColumn(),
    'enviados' => (int) $pdo->query("SELECT COUNT(*) FROM email_fila WHERE status = 'enviado'")->fetchColumn(),
    'falhas' => (int) $pdo->query("SELECT COUNT(*) FROM email_fila WHERE status = 'falhou'")->fetchColumn(),
];

require_once dirname(__DIR__) . '/app/views/includes/head.php';
?>
<aside class="fixed top-0 left-0 h-full w-64 bg-primary text-on-primary z-40 flex flex-col">
  <div class="flex items-center justify-center py-8">
    <div class="text-xl font-headline-lg tracking-[0.4em] text-white">LUPIERE ADMIN</div>
  </div>
  <nav class="flex-1 flex-col pt-6 space-y-4">
    <a href="index.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">dashboard</span><span class="ml-3">Dashboard</span></a>
    <a href="produtos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">inventory_2</span><span class="ml-3">Produtos</span></a>
    <a href="categorias.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">category</span><span class="ml-3">Categorias</span></a>
    <a href="pedidos.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">list_alt</span><span class="ml-3">Pedidos</span></a>
    <a href="banners.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">image</span><span class="ml-3">Banners</span></a>
    <a href="emails.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">mail</span><span class="ml-3">E-mails</span></a>
    <a href="administradores.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"><span class="notranslate material-symbols-outlined" translate="no">admin_panel_settings</span><span class="ml-3">Administradores</span></a>
    <a href="../logout.php" class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors mt-auto"><span class="notranslate material-symbols-outlined" translate="no">logout</span><span class="ml-3">Sair</span></a>
  </nav>
</aside>

<main class="flex-grow ml-64 flex flex-col">
  <header class="fixed top-0 left-64 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-16 flex items-center">
    <div class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto">
      <div class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]">Servicos de Email</div>
      <form method="post">
        <input type="hidden" name="acao" value="processar">
        <button class="bg-primary-container text-white py-3 px-4 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all">Processar fila</button>
      </form>
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

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-surface rounded-lg border border-outline/20 p-6"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Inscritos</p><p class="font-headline-md text-headline-md text-primary"><?php echo $stats['inscritos']; ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-6"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Pendentes</p><p class="font-headline-md text-headline-md text-primary"><?php echo $stats['pendentes']; ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-6"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Enviados</p><p class="font-headline-md text-headline-md text-primary"><?php echo $stats['enviados']; ?></p></div>
        <div class="bg-surface rounded-lg border border-outline/20 p-6"><p class="font-label-caps text-label-caps text-on-surface-variant/60">Falhas</p><p class="font-headline-md text-headline-md text-primary"><?php echo $stats['falhas']; ?></p></div>
      </div>

      <section class="bg-surface rounded-lg border border-outline/20 p-6">
        <h2 class="font-headline-md text-headline-md mb-6">Configuracao SMTP</h2>
        <form method="post" class="space-y-6">
          <input type="hidden" name="acao" value="config_smtp">
          <div class="grid gap-4 md:grid-cols-4">
            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">Host SMTP</label>
              <input name="host" value="<?php echo escapar($config_email['host'] ?? ''); ?>" placeholder="smtp.gmail.com" class="w-full form-input-bespoke py-3 text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Porta</label>
              <input type="number" name="porta" value="<?php echo escapar($config_email['porta'] ?? 587); ?>" class="w-full form-input-bespoke py-3 text-primary">
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Seguranca</label>
              <select name="criptografia" class="w-full form-input-bespoke py-3 text-primary">
                <?php foreach (['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'Nenhuma'] as $valor => $label): ?>
                  <option value="<?php echo $valor; ?>" <?php echo (($config_email['criptografia'] ?? 'tls') === $valor) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">Usuario SMTP</label>
              <input name="usuario" value="<?php echo escapar($config_email['usuario'] ?? ''); ?>" class="w-full form-input-bespoke py-3 text-primary">
            </div>
            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">Senha SMTP</label>
              <input type="password" name="senha" placeholder="<?php echo !empty($config_email['senha']) ? 'Senha salva - deixe vazio para manter' : ''; ?>" class="w-full form-input-bespoke py-3 text-primary" autocomplete="new-password">
            </div>
            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">E-mail remetente</label>
              <input type="email" name="remetente_email" value="<?php echo escapar($config_email['remetente_email'] ?? ''); ?>" placeholder="contato@seudominio.com" class="w-full form-input-bespoke py-3 text-primary">
            </div>
            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">Nome remetente</label>
              <input name="remetente_nome" value="<?php echo escapar($config_email['remetente_nome'] ?? 'LUPIERE'); ?>" class="w-full form-input-bespoke py-3 text-primary">
            </div>
          </div>
          <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-3"><input type="checkbox" name="ativo" <?php echo !empty($config_email['ativo']) ? 'checked' : ''; ?>><span>Usar SMTP para envios reais</span></label>
            <button class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all">Salvar SMTP</button>
          </div>
        </form>
        <form method="post" class="mt-6 flex gap-3 items-end">
          <input type="hidden" name="acao" value="teste_smtp">
          <div class="flex-1">
            <label class="block font-label-caps text-label-caps mb-2">Enviar teste para</label>
            <input type="email" name="email_teste" value="<?php echo escapar($config_email['remetente_email'] ?? ''); ?>" class="w-full form-input-bespoke py-3 text-primary">
          </div>
          <button class="border border-outline/30 text-primary py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-surface-container-low transition-all">Enviar teste</button>
        </form>
      </section>

      <section class="bg-surface rounded-lg border border-outline/20 p-6">
        <h2 class="font-headline-md text-headline-md mb-6">Nova campanha</h2>
        <form method="post" class="space-y-6">
          <input type="hidden" name="acao" value="campanha">
          <div class="grid gap-4 md:grid-cols-2">
            <div><label class="block font-label-caps text-label-caps mb-2">Titulo interno</label><input name="titulo" class="w-full form-input-bespoke py-3 text-primary" required></div>
            <div><label class="block font-label-caps text-label-caps mb-2">Assunto</label><input name="assunto" class="w-full form-input-bespoke py-3 text-primary" required></div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Tipo</label>
              <select name="tipo" class="w-full form-input-bespoke py-3 text-primary">
                <option value="promocao">Promocao</option>
                <option value="aviso">Aviso</option>
                <option value="status">Status de compra</option>
                <option value="desejos">Lembrete de desejos</option>
                <option value="carrinho">Lembrete de carrinho</option>
              </select>
            </div>
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Publico</label>
              <select name="publico" id="publico" class="w-full form-input-bespoke py-3 text-primary">
                <option value="inscritos">Inscritos na newsletter</option>
                <option value="clientes">Todos os clientes</option>
                <option value="com_pedidos">Clientes com pedidos</option>
                <option value="com_desejos">Clientes com lista de desejos</option>
                <option value="selecionados">Selecionar manualmente</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block font-label-caps text-label-caps mb-2">Conteudo do email</label>
            <textarea name="conteudo_html" rows="8" class="w-full border border-outline/30 rounded-lg p-4 bg-white text-primary" placeholder="<p>Escreva sua promocao ou aviso aqui.</p>" required></textarea>
          </div>
          <div id="usuariosSelecionados" class="border border-outline/20 rounded-lg p-4 max-h-64 overflow-y-auto hidden">
            <p class="font-label-caps text-label-caps mb-3">Destinatarios manuais</p>
            <div class="grid md:grid-cols-2 gap-3">
              <?php foreach ($usuarios as $usuario): ?>
                <label class="flex items-start gap-3 text-sm">
                  <input type="checkbox" name="usuarios[]" value="<?php echo (int) $usuario['id']; ?>" class="mt-1">
                  <span><?php echo escapar($usuario['nome']); ?><br><span class="text-on-surface-variant/60"><?php echo escapar($usuario['email']); ?></span></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="flex justify-end">
            <button class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all">Criar e enfileirar</button>
          </div>
        </form>
      </section>

      <div class="grid gap-8 lg:grid-cols-2">
        <section class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md mb-6">Nova automacao</h2>
          <form method="post" class="space-y-5">
            <input type="hidden" name="acao" value="automacao">
            <div><label class="block font-label-caps text-label-caps mb-2">Nome</label><input name="nome" class="w-full form-input-bespoke py-3 text-primary" required></div>
            <div class="grid gap-4 md:grid-cols-2">
              <div>
                <label class="block font-label-caps text-label-caps mb-2">Gatilho</label>
                <select name="tipo" class="w-full form-input-bespoke py-3 text-primary">
                  <option value="promocao">Promocao recorrente</option>
                  <option value="desejos">Lembrete de lista de desejos</option>
                  <option value="carrinho">Lembrete de carrinho abandonado</option>
                </select>
              </div>
              <div><label class="block font-label-caps text-label-caps mb-2">Intervalo em minutos</label><input type="number" name="intervalo_minutos" min="15" value="1440" class="w-full form-input-bespoke py-3 text-primary" required></div>
            </div>
            <div><label class="block font-label-caps text-label-caps mb-2">Assunto</label><input name="assunto" class="w-full form-input-bespoke py-3 text-primary" required></div>
            <div><label class="block font-label-caps text-label-caps mb-2">Conteudo</label><textarea name="conteudo_html" rows="5" class="w-full border border-outline/30 rounded-lg p-4 bg-white text-primary" required></textarea></div>
            <label class="flex items-center gap-3"><input type="checkbox" name="ativo" checked><span>Ativa</span></label>
            <button class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all">Salvar automacao</button>
          </form>
        </section>

        <section class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md mb-6">Adicionar inscrito</h2>
          <form method="post" class="space-y-5 mb-8">
            <input type="hidden" name="acao" value="inscrever">
            <div><label class="block font-label-caps text-label-caps mb-2">Nome</label><input name="nome" class="w-full form-input-bespoke py-3 text-primary"></div>
            <div><label class="block font-label-caps text-label-caps mb-2">E-mail</label><input type="email" name="email" class="w-full form-input-bespoke py-3 text-primary" required></div>
            <button class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all">Inscrever</button>
          </form>
          <h3 class="font-headline-md text-[24px] mb-4">Inscritos recentes</h3>
          <div class="space-y-3">
            <?php foreach ($inscritos as $inscrito): ?>
              <div class="border-b border-outline/10 pb-3">
                <p class="text-primary"><?php echo escapar($inscrito['nome'] ?: 'Sem nome'); ?></p>
                <p class="text-sm text-on-surface-variant"><?php echo escapar($inscrito['email']); ?> - <?php echo $inscrito['ativo'] ? 'ativo' : 'inativo'; ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      </div>

      <div class="grid gap-8 lg:grid-cols-2">
        <section class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md mb-6">Automacoes</h2>
          <div class="space-y-4">
            <?php foreach ($automacoes as $automacao): ?>
              <div class="border border-outline/20 rounded-lg p-4">
                <div class="flex justify-between gap-4"><strong><?php echo escapar($automacao['nome']); ?></strong><span><?php echo $automacao['ativo'] ? 'Ativa' : 'Inativa'; ?></span></div>
                <p class="text-sm text-on-surface-variant">Tipo: <?php echo escapar($automacao['tipo']); ?> - a cada <?php echo (int) $automacao['intervalo_minutos']; ?> min</p>
                <p class="text-sm text-on-surface-variant">Proxima: <?php echo escapar($automacao['proxima_execucao'] ?? '-'); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="bg-surface rounded-lg border border-outline/20 p-6">
          <h2 class="font-headline-md text-headline-md mb-6">Fila e historico</h2>
          <div class="space-y-4">
            <?php foreach ($fila as $email): ?>
              <div class="border-b border-outline/10 pb-3">
                <div class="flex justify-between gap-4"><strong><?php echo escapar($email['assunto']); ?></strong><span><?php echo escapar($email['status']); ?></span></div>
                <p class="text-sm text-on-surface-variant"><?php echo escapar($email['email']); ?> - <?php echo escapar($email['data_criacao']); ?></p>
                <?php if (!empty($email['erro'])): ?><p class="text-sm text-red-600"><?php echo escapar($email['erro']); ?></p><?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      </div>
    </div>
  </div>
</main>

<script>
  const publico = document.getElementById('publico');
  const usuariosSelecionados = document.getElementById('usuariosSelecionados');
  function atualizarSelecaoUsuarios() {
    usuariosSelecionados.classList.toggle('hidden', publico.value !== 'selecionados');
  }
  publico.addEventListener('change', atualizarSelecaoUsuarios);
  atualizarSelecaoUsuarios();
</script>
</body>
</html>
