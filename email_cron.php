<?php
require_once __DIR__ . '/app/core/funcoes.php';

$token_configurado = getenv('LUPIERE_CRON_TOKEN') ?: '';
$token_recebido = $_GET['token'] ?? ($argv[1] ?? '');
if ($token_configurado !== '' && !hash_equals($token_configurado, (string) $token_recebido)) {
    http_response_code(403);
    echo "Token invalido." . PHP_EOL;
    exit(1);
}

$automacoes = processar_automacoes_email();
$fila = processar_fila_emails(50);

header('Content-Type: text/plain; charset=utf-8');
echo "Automacoes geraram: {$automacoes}" . PHP_EOL;
echo "Emails enviados: {$fila['enviados']}" . PHP_EOL;
echo "Falhas: {$fila['falhas']}" . PHP_EOL;
