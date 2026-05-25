<?php
require_once __DIR__ . '/app/core/funcoes.php';

$token = mercado_pago_access_token();
if ($token === '') {
    http_response_code(204);
    exit();
}

$payment_id = $_GET['id'] ?? $_GET['data_id'] ?? ($_GET['data.id'] ?? null);
$topic = $_GET['topic'] ?? $_GET['type'] ?? '';

if (!$payment_id || !str_contains((string) $topic, 'payment')) {
    http_response_code(204);
    exit();
}

$ch = curl_init('https://api.mercadopago.com/v1/payments/' . rawurlencode($payment_id));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token],
    CURLOPT_TIMEOUT => 15,
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $http_code < 200 || $http_code >= 300) {
    http_response_code(204);
    exit();
}

$payment = json_decode($response, true);
$pedido_id = (int) ($payment['external_reference'] ?? 0);
$status = $payment['status'] ?? 'pending';

$mapa = [
    'approved' => 'pago',
    'pending' => 'aguardando_pagamento',
    'in_process' => 'processando_pagamento',
    'rejected' => 'recusado',
    'cancelled' => 'cancelado',
    'refunded' => 'estornado',
    'charged_back' => 'chargeback',
];

if ($pedido_id > 0) {
    atualizar_status_pagamento_pedido($pedido_id, $mapa[$status] ?? $status, $status === 'approved' ? date('Y-m-d H:i:s') : null);
}

http_response_code(200);
echo 'ok';
