<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';
proteger_pagina(); // Redireciona se não estiver logado

// Verificar se carrinho está vazio
if (empty($_SESSION['carrinho'])) {
    $_SESSION['carrinho_erro'] = 'Seu carrinho está vazio';
    header('Location: carrinho.php');
    exit();
}

$titulo_pagina = 'Finalizar compra';
require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';

// Calcular totais
$subtotal = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}

$desconto = 0;
if (isset($_SESSION['cupom_desconto'])) {
    $desconto = $subtotal * ($_SESSION['cupom_desconto'] / 100);
}
$total = $subtotal - $desconto;
?>
<div class="flex-grow flex items-center justify-center py-section-gap px-gutter">
  <div class="max-w-container-max w-full">
    <?php
    // Exibir mensagens
    if (isset($_SESSION['checkout_sucesso'])) {
        echo mensagem_sucesso($_SESSION['checkout_sucesso']);
        unset($_SESSION['checkout_sucesso']);
    }
    if (isset($_SESSION['checkout_erro'])) {
        echo mensagem_erro($_SESSION['checkout_erro']);
        unset($_SESSION['checkout_erro']);
    }
    ?>

    <div class="bg-surface rounded-lg border border-outline/20 p-8">
      <h2 class="font-headline-md text-headline-md mb-6">Finalizar compra</h2>

      <!-- Resumo do pedido -->
      <div class="mb-8">
        <h3 class="font-headline-sm text-headline-sm mb-4">Resumo do pedido</h3>
        <div class="space-y-4">
          <?php foreach ($_SESSION['carrinho'] as $index => $item): ?>
            <div class="flex items-start gap-4 py-3 border-b border-outline/10 last:border-b-0">
              <!-- Imagem -->
              <div class="flex-shrink-0">
                <?php if (imagem_produto_disponivel($item['imagem'] ?? '')): ?>
                  <img src="<?php echo escapar(imagem_produto_url($item['imagem'])); ?>" alt="<?php echo escapar($item['nome']); ?>" class="w-16 h-16 object-contain rounded-lg">
                <?php else: ?>
                  <div class="w-16 h-16 bg-surface-container flex items-center justify-center rounded-lg">
                    <span class="material-symbols-outlined text-on-surface-variant/60">inventory_2</span>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Informações -->
              <div class="flex-1 space-y-1">
                <p class="font-body-sm text-body-sm line-clamp-1"><?php echo escapar($item['nome']); ?></p>
                <p class="text-xs text-on-surface-variant/60">
                  <?php echo $item['quantidade']; ?>x <?php echo formatar_moeda($item['preco']); ?>
                </p>
              </div>

              <!-- Preço total do item -->
              <div class="flex-shrink-0 text-right">
                <p class="font-body-sm text-body-sm"><?php echo formatar_moeda($item['preco'] * $item['quantidade']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-6 pt-4 border-t border-outline/20">
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="font-label-caps text-label-caps">Subtotal:</span>
              <span class="font-headline-sm text-headline-sm"><?php echo formatar_moeda($subtotal); ?></span>
            </div>
            <?php if ($desconto > 0): ?>
              <div class="flex justify-between">
                <span class="font-label-caps text-label-caps">Desconto (<?php echo $_SESSION['cupom_desconto']; ?>%):</span>
                <span class="font-headline-sm text-headline-sm text-green-600"><?php echo formatar_moeda($desconto); ?></span>
              </div>
            <?php endif; ?>
            <div class="flex justify-between items-center">
              <span class="font-label-caps text-label-caps font-semibold">Total:</span>
              <span class="font-headline-lg text-headline-lg text-primary font-semibold">
                <?php echo formatar_moeda($total); ?>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Formulario de entrega -->
      <form action="finalizar.php" method="POST" class="space-y-6">
        <div class="space-y-4">
          <h3 class="font-headline-sm text-headline-sm mb-4">Informações de entrega</h3>

          <div class="grid gap-4 md:grid-cols-2">
            <div>
              <label class="block font-label-caps text-label-caps mb-2">Nome completo:</label>
              <input
                type="text"
                name="nome"
                class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                required
              >
            </div>

            <div>
              <label class="block font-label-caps text-label-caps mb-2">E-mail:</label>
              <input
                type="email"
                name="email"
                class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                required
              >
            </div>

            <div class="md:col-span-2">
              <label class="block font-label-caps text-label-caps mb-2">Endereço completo:</label>
              <textarea
                name="endereco"
                class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                rows="3"
                placeholder="Rua, número, complemento, bairro, cidade, estado, CEP"
                required
              ></textarea>
            </div>
          </div>

          <div class="mt-4 pt-4 border-t border-outline/20">
            <label class="block font-label-caps text-label-caps mb-2">Observações (opcional):</label>
            <textarea
              name="observacoes"
              class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
              rows="2"
              placeholder="Ex: Deixe com o porteiro, prefere entrega em horário específico..."
            ></textarea>
          </div>
        </div>

        <div class="mt-8 pt-6 border-t border-outline/20">
          <div class="flex justify-end">
            <button
              type="submit"
              class="bg-primary-container text-white py-4 px-8 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
            >
              Finalizar compra
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/app/views/includes/footer.php';
?>
