<?php
session_start();
require_once __DIR__ . '/app/core/oauth.php';

try {
    limitar_requisicoes('oauth_callback', 15, 60);
    $fluxo = $_SESSION['oauth_fluxo'] ?? [];
    unset($_SESSION['oauth_fluxo']);
    $state = (string) ($_POST['state'] ?? $_GET['state'] ?? '');
    if (!$fluxo || ($fluxo['expira'] ?? 0) < time() || !hash_equals((string) ($fluxo['state'] ?? ''), $state)) {
        throw new RuntimeException('A sessao do login social expirou. Tente novamente.');
    }
    if (!empty($_POST['error']) || !empty($_GET['error'])) {
        throw new RuntimeException('O login social foi cancelado ou recusado.');
    }
    $codigo = (string) ($_POST['code'] ?? $_GET['code'] ?? '');
    if ($codigo === '') {
        throw new RuntimeException('O provedor nao retornou o codigo de acesso.');
    }
    $provedor = $fluxo['provedor'];
    $config = oauth_configuracao($provedor);
    $tokens = oauth_http_post($config['token_url'], [
        'grant_type' => 'authorization_code',
        'code' => $codigo,
        'redirect_uri' => $config['redirect_uri'],
        'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret'],
    ]);
    $claims = oauth_validar_id_token($tokens['id_token'] ?? '', $config, $fluxo['nonce']);
    $nome = '';
    if ($provedor === 'apple' && !empty($_POST['user'])) {
        $dados_apple = json_decode($_POST['user'], true);
        $nome = trim(($dados_apple['name']['firstName'] ?? '') . ' ' . ($dados_apple['name']['lastName'] ?? ''));
    }
    $usuario = oauth_obter_ou_criar_usuario($provedor, $claims, $nome);
    oauth_iniciar_sessao_usuario($usuario);
    header('Location: ' . (!empty($usuario['admin']) ? 'admin/index.php' : 'perfil.php'));
    exit;
} catch (Throwable $e) {
    error_log('Falha no callback OAuth: ' . $e->getMessage());
    $_SESSION['login_erro'] = $e->getMessage();
    header('Location: login.php');
    exit;
}
