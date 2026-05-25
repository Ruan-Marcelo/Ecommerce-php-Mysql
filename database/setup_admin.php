<?php
require_once dirname(__DIR__) . '/app/core/funcoes.php';

$admin_email = 'admin@lupiere.com';
$admin_senha = 'Admin@123';

$senhas_normalizadas = normalizar_hashes_senhas_usuarios();
$admin_ok = criar_ou_atualizar_admin_padrao($admin_email, $admin_senha);
$categoria_acessorios = garantir_categoria_acessorios();

header('Content-Type: text/plain; charset=utf-8');

if (!$admin_ok) {
    http_response_code(500);
    echo "Erro ao criar ou atualizar o administrador padrão." . PHP_EOL;
    exit;
}

echo "Administrador padrão pronto." . PHP_EOL;
echo "Email: {$admin_email}" . PHP_EOL;
echo "Senha: {$admin_senha}" . PHP_EOL;
echo "Senhas antigas convertidas para hash: {$senhas_normalizadas}" . PHP_EOL;
echo "Categoria Acessórios: " . ($categoria_acessorios['nome'] ?? 'erro') . PHP_EOL;
