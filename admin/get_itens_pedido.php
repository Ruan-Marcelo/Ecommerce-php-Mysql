<?php
session_start();
require_once dirname(__DIR__) . '/app/core/funcoes.php';
proteger_pagina_admin();

header('Content-Type: application/json');

$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pedido_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID do pedido inválido']);
    exit();
}

// Obter itens do pedido
$itens = obter_itens_pedido($pedido_id);

if ($itens === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao obter itens do pedido']);
    exit();
}

echo json_encode([
    'success' => true,
    'itens' => $itens
]);
?>