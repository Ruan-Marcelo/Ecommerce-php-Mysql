<?php
require_once dirname(__DIR__) . '/app/core/funcoes.php';

echo "Testing database connection...<br>";

try {
    // Test categorias
    $categorias = obter_categorias();
    echo "Categorias encontradas: " . count($categorias) . "<br>";
    if (!empty($categorias)) {
        echo "Primeira categoria: " . $categorias[0]['nome'] . "<br>";
    }

    // Test produtos
    $produtos = obter_produtos();
    echo "Produtos encontrados: " . count($produtos) . "<br>";
    if (!empty($produtos)) {
        echo "Primeiro produto: " . $produtos[0]['nome'] . " - R$ " . number_format($produtos[0]['preco'], 2, ',', '.') . "<br>";
    }

    // Test produto por ID
    if (!empty($produtos)) {
        $produto_id = $produtos[0]['id'];
        $produto = obter_produto_por_id($produto_id);
        echo "Produto por ID {$produto_id}: " . ($produto ? $produto['nome'] : 'Não encontrado') . "<br>";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>