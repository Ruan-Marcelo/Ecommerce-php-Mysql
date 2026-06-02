<?php
session_start();
require_once __DIR__ . '/app/core/oauth.php';

try {
    limitar_requisicoes('oauth_inicio', 15, 60);
    $provedor = strtolower(trim($_GET['provedor'] ?? ''));
    $config = oauth_configuracao($provedor);
    $state = bin2hex(random_bytes(24));
    $nonce = bin2hex(random_bytes(24));
    $_SESSION['oauth_fluxo'] = ['provedor' => $provedor, 'state' => $state, 'nonce' => $nonce, 'expira' => time() + 600];
    $parametros = [
        'client_id' => $config['client_id'],
        'redirect_uri' => $config['redirect_uri'],
        'response_type' => 'code',
        'scope' => $config['scope'],
        'state' => $state,
        'nonce' => $nonce,
    ];
    if ($provedor === 'google') {
        $parametros['prompt'] = 'select_account';
    } else {
        $parametros['response_mode'] = 'form_post';
    }
    header('Location: ' . $config['authorize_url'] . '?' . http_build_query($parametros));
    exit;
} catch (Throwable $e) {
    error_log('Falha ao iniciar OAuth: ' . $e->getMessage());
    $_SESSION['login_erro'] = $e->getMessage();
    header('Location: login.php');
    exit;
}
