<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: produtos.php');
    exit();
}

validar_csrf();
limitar_requisicoes('toggle_desejo', 30, 300);

$produto_id = (int) ($_POST['produto_id'] ?? 0);
$redirect = $_POST['redirect'] ?? 'produtos.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

if ($produto_id > 0) {
    alternar_lista_desejos($_SESSION['usuario_id'], $produto_id);
}

redirect_seguro($redirect, 'produtos.php');
