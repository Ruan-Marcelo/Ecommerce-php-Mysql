<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';
proteger_pagina(); // Redireciona se não estiver logado

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}
validar_csrf();
limitar_requisicoes('finalizar_compra', 5, 300);

// Verificar se carrinho está vazio
if (empty($_SESSION['carrinho'])) {
    $_SESSION['checkout_erro'] = 'Seu carrinho está vazio';
    header('Location: carrinho.php');
    exit();
}

// Sanitizar e validar dados de entrega
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');
$forma_pagamento = $_POST['forma_pagamento'] ?? 'pix';

$errors = [];

if (empty($nome)) {
    $errors[] = 'Nome é obrigatório';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email válido é obrigatório';
}

if (empty($endereco)) {
    $errors[] = 'Endereço é obrigatório';
}

if (!in_array($forma_pagamento, ['pix', 'cartao', 'boleto'], true)) {
    $errors[] = 'Forma de pagamento inválida';
}

if (!empty($errors)) {
    $_SESSION['checkout_erro'] = implode('<br>', $errors);
    header('Location: checkout.php');
    exit();
}

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

// Finalizar compra
$pedido_id = finalizar_compra(
    $_SESSION['usuario_id'],
    $_SESSION['carrinho'],
    $total,
    $endereco . (empty($observacoes) ? '' : ' - Observações: ' . $observacoes),
    $forma_pagamento
);

if ($pedido_id) {
    desativar_carrinho_abandonado_usuario($_SESSION['usuario_id']);
    $pedido = obter_pedido_por_id($pedido_id);
    $preferencia = criar_preferencia_mercado_pago($pedido, $_SESSION['carrinho']);
    if (!empty($preferencia['sucesso']) && !empty($preferencia['checkout_url'])) {
        atualizar_checkout_pagamento_pedido($pedido_id, 'mercado_pago', $preferencia['preference_id'], $preferencia['checkout_url']);
        $_SESSION['checkout_pagamento_url'] = $preferencia['checkout_url'];
    } elseif (mercado_pago_access_token() !== '') {
        $_SESSION['checkout_erro'] = $preferencia['erro'] ?? 'Erro ao criar checkout real.';
    }

    // Limpar carrinho e cupom
    limpar_carrinho();
    unset($_SESSION['cupom_desconto']);

    $_SESSION['finalizar_sucesso'] = 'Pedido realizado com sucesso! Número do pedido: #'.$pedido_id;
    header('Location: pedido_confirmado.php?id=' . $pedido_id);
    exit();
} else {
    $_SESSION['checkout_erro'] = 'Erro ao processar o pedido. Tente novamente.';
    header('Location: checkout.php');
    exit();
}
?>
