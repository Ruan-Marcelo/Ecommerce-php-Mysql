<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

// Verificar se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = isset($_POST['produto_id']) ? intval($_POST['produto_id']) : 0;
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;

    // Validar o produto_id e a quantidade
    if ($produto_id <= 0 || $quantidade <= 0) {
        header("Location: produtos.php");
        exit();
    }

    // Obter informações do produto
    $produto = obter_produto_por_id($produto_id);
    if (!$produto) {
        header("Location: produtos.php");
        exit();
    }

    // Verificar se há estoque suficiente
    if ($produto['estoque'] < $quantidade) {
        header("Location: produto.php?id=$produto_id&error=estoque");
        exit();
    }

    // Inicializar o carrinho na sessão se não existir
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Verificar se o produto já está no carrinho
    $produto_existente = false;
    foreach ($_SESSION['carrinho'] as $index => $item) {
        if ($item['produto_id'] == $produto_id) {
            // Atualizar a quantidade
            $nova_quantidade = $item['quantidade'] + $quantidade;
            if ($nova_quantidade > $produto['estoque']) {
                header("Location: produto.php?id=$produto_id&error=estoque");
                exit();
            }
            $_SESSION['carrinho'][$index]['quantidade'] = $nova_quantidade;
            $produto_existente = true;
            break;
        }
    }

    // Se o produto não existir no carrinho, adicionar novo item
    if (!$produto_existente) {
        $_SESSION['carrinho'][] = [
            'produto_id' => $produto_id,
            'nome' => $produto['nome'],
            'preco' => $produto['preco'],
            'quantidade' => $quantidade,
            'imagem' => $produto['imagem'] ?? 'https://via.placeholder.com/150'
        ];
    }

    // Redirecionar para o carrinho
    header("Location: carrinho.php");
    exit();
} else {
    // Se não for POST, redirecionar para a página de produtos
    header("Location: produtos.php");
    exit();
}
?>