<?php
session_start();
require_once 'funcoes.php';

echo "Testing complete flow...<br>";

// 1. Test login with existing user (assuming there's a test user)
echo "1. Testing login...<br>";
$_SESSION['usuario_id'] = 1; // Simulate logged in user
$_SESSION['usuario_nome'] = 'Test User';
echo "User simulated as logged in<br>";

// 2. Test adding product to cart
echo "2. Testing add to cart...<br>";
// Get a product first
$produtos = obter_produtos(1, 0); // Get first product
if (!empty($produtos)) {
    $produto_id = $produtos[0]['id'];
    echo "Adding product ID: {$produto_id} to cart<br>";

    // Simulate adding to cart
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    $_SESSION['carrinho'][] = [
        'produto_id' => $produto_id,
        'nome' => $produtos[0]['nome'],
        'preco' => $produtos[0]['preco'],
        'quantidade' => 1,
        'imagem' => $produtos[0]['imagem'] ?? 'https://via.placeholder.com/150'
    ];

    echo "Product added to cart. Cart items: " . count($_SESSION['carrinho']) . "<br>";
}

// 3. Test cart calculation
echo "3. Testing cart calculation...<br>";
$total = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
}
echo "Cart total: R$ " . number_format($total, 2, ',', '.') . "<br>";

// 4. Test finalizar_compra function (without actually inserting to DB for now)
echo "4. Testing finalizar_compra function...<br>";
if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    // Just test that the function exists and doesn't throw error
    try {
        // We won't actually call it to avoid DB insert, but we can check if function exists
        if (function_exists('finalizar_compra')) {
            echo "finalizar_compra function exists<br>";
        } else {
            echo "finalizar_compra function NOT found<br>";
        }
    } catch (Exception $e) {
        echo "Error checking finalizar_compra: " . $e->getMessage() . "<br>";
    }
}

// 5. Test limpar_carrinho function
echo "5. Testing limpar_carrinho function...<br>";
if (function_exists('limpar_carrinho')) {
    echo "limpar_carrinho function exists<br>";
    limpar_carrinho();
    echo "Cart cleared. Items remaining: " . (isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0) . "<br>";
} else {
    echo "limpar_carrinho function NOT found<br>";
}

echo "<br>Test complete!";
?>