<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';
proteger_pagina();

$pedido_id = (int) ($_GET['id'] ?? 0);
$pedido = $pedido_id > 0 ? obter_pedido_por_id($pedido_id) : null;

if (!$pedido || (int) $pedido['usuario_id'] !== (int) $_SESSION['usuario_id']) {
    header('Location: historico.php');
    exit();
}

$titulo_pagina = 'Pedido confirmado';
$formas = [
    'pix' => 'Pix',
    'cartao' => 'Cartão',
    'boleto' => 'Boleto',
];

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="pt-32 pb-24 px-gutter">
  <div class="max-w-[900px] mx-auto">
    <?php
    if (isset($_SESSION['finalizar_sucesso'])) {
        echo mensagem_sucesso($_SESSION['finalizar_sucesso']);
        unset($_SESSION['finalizar_sucesso']);
    }
    ?>

    <div class="bg-surface border border-outline/20 rounded-lg p-8 space-y-8">
      <header>
        <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">Pedido #<?php echo (int) $pedido['id']; ?></p>
        <h1 class="font-headline-lg text-headline-lg text-primary">Pedido criado</h1>
        <p class="text-on-surface-variant mt-4">
          Forma de pagamento: <strong><?php echo escapar($formas[$pedido['forma_pagamento']] ?? $pedido['forma_pagamento']); ?></strong>
        </p>
        <p class="text-on-surface-variant">
          Status do pagamento: <strong><?php echo escapar($pedido['status_pagamento']); ?></strong>
        </p>
      </header>

      <?php if (!empty($pedido['checkout_url']) || !empty($_SESSION['checkout_pagamento_url'])): ?>
        <?php $checkout_url = $pedido['checkout_url'] ?: $_SESSION['checkout_pagamento_url']; unset($_SESSION['checkout_pagamento_url']); ?>
        <div class="border border-green-500/20 rounded-lg p-6 bg-green-500/10">
          <h2 class="font-headline-md text-headline-md text-primary mb-4">Pagamento real disponível</h2>
          <p class="text-on-surface-variant mb-4">Finalize o pagamento no ambiente seguro do Mercado Pago.</p>
          <a href="<?php echo escapar($checkout_url); ?>" class="inline-block bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em]" rel="noopener noreferrer">
            Ir para pagamento
          </a>
        </div>
      <?php endif; ?>

      <?php if ($pedido['forma_pagamento'] === 'pix'): ?>
        <div class="border border-outline/20 rounded-lg p-6 bg-surface-container-low">
          <h2 class="font-headline-md text-headline-md text-primary mb-4">Pix Copia e Cola</h2>
          <textarea readonly class="w-full form-input-bespoke py-3 text-primary" rows="4"><?php echo escapar($pedido['pix_copia_cola']); ?></textarea>
          <p class="text-sm text-on-surface-variant mt-3">Simulação interna. Em produção, esse código deve vir da API de pagamento.</p>
        </div>
      <?php elseif ($pedido['forma_pagamento'] === 'cartao'): ?>
        <div class="border border-outline/20 rounded-lg p-6 bg-surface-container-low">
          <h2 class="font-headline-md text-headline-md text-primary mb-4">Cartão</h2>
          <p class="text-on-surface-variant">Pagamento registrado como pendente. A captura real depende de integração com API segura de pagamento.</p>
        </div>
      <?php else: ?>
        <div class="border border-outline/20 rounded-lg p-6 bg-surface-container-low">
          <h2 class="font-headline-md text-headline-md text-primary mb-4">Boleto</h2>
          <p class="text-on-surface-variant">Boleto registrado como pendente de emissão. Em produção, a linha digitável vem da API do provedor.</p>
        </div>
      <?php endif; ?>

      <div class="flex flex-col sm:flex-row gap-3">
        <a href="historico.php" class="bg-primary-container text-white py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] text-center">Ver histórico</a>
        <a href="produtos.php" class="border border-outline/30 text-primary py-3 px-5 font-label-caps text-label-caps tracking-[0.2em] text-center">Continuar comprando</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
