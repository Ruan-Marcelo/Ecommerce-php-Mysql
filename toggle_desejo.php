<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

$produto_id = (int) ($_POST['produto_id'] ?? $_GET['produto_id'] ?? 0);
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'produtos.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

if ($produto_id > 0) {
    alternar_lista_desejos($_SESSION['usuario_id'], $produto_id);
}

header('Location: ' . $redirect);
exit();
