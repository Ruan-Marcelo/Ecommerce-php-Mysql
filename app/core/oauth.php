<?php
require_once __DIR__ . '/funcoes.php';

function oauth_base_url() {
    $configurada = rtrim((string) getenv('APP_URL'), '/');
    if ($configurada !== '') {
        return $configurada;
    }
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $diretorio = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    return ($https ? 'https' : 'http') . '://' . $host . rtrim($diretorio, '/');
}

function oauth_configuracao($provedor) {
    $callback = oauth_base_url() . '/oauth_callback.php';
    $configuracoes = [
        'google' => [
            'client_id' => getenv('GOOGLE_CLIENT_ID') ?: '',
            'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: '',
            'authorize_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token_url' => 'https://oauth2.googleapis.com/token',
            'jwks_url' => 'https://www.googleapis.com/oauth2/v3/certs',
            'issuer' => ['https://accounts.google.com', 'accounts.google.com'],
            'scope' => 'openid email profile',
            'redirect_uri' => $callback,
        ],
        'apple' => [
            'client_id' => getenv('APPLE_CLIENT_ID') ?: '',
            'client_secret' => getenv('APPLE_CLIENT_SECRET') ?: '',
            'authorize_url' => 'https://appleid.apple.com/auth/authorize',
            'token_url' => 'https://appleid.apple.com/auth/token',
            'jwks_url' => 'https://appleid.apple.com/auth/keys',
            'issuer' => ['https://appleid.apple.com'],
            'scope' => 'name email',
            'redirect_uri' => $callback,
        ],
    ];
    if (!isset($configuracoes[$provedor])) {
        throw new RuntimeException('Provedor de login invalido.');
    }
    $config = $configuracoes[$provedor];
    if ($config['client_id'] === '' || $config['client_secret'] === '') {
        throw new RuntimeException('Login com ' . ucfirst($provedor) . ' ainda nao foi configurado.');
    }
    return $config;
}

function oauth_http_post($url, array $campos) {
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($campos),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    $resposta = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $erro = curl_error($curl);
    curl_close($curl);
    if ($resposta === false || $status < 200 || $status >= 300) {
        error_log('Falha OAuth HTTP POST: ' . $status . ' ' . $erro);
        throw new RuntimeException('Nao foi possivel concluir o login social.');
    }
    return json_decode($resposta, true, 512, JSON_THROW_ON_ERROR);
}

function oauth_http_json($url) {
    $curl = curl_init($url);
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
    $resposta = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($resposta === false || $status < 200 || $status >= 300) {
        throw new RuntimeException('Nao foi possivel validar o login social.');
    }
    return json_decode($resposta, true, 512, JSON_THROW_ON_ERROR);
}

function oauth_base64url_decode($valor) {
    return base64_decode(strtr($valor, '-_', '+/'));
}

function oauth_asn1($tipo, $valor) {
    $tamanho = strlen($valor);
    if ($tamanho < 128) {
        return chr($tipo) . chr($tamanho) . $valor;
    }
    $bytes = ltrim(pack('N', $tamanho), "\x00");
    return chr($tipo) . chr(0x80 | strlen($bytes)) . $bytes . $valor;
}

function oauth_inteiro_asn1($valor) {
    $valor = ltrim($valor, "\x00");
    if ($valor === '' || (ord($valor[0]) & 0x80)) {
        $valor = "\x00" . $valor;
    }
    return oauth_asn1(0x02, $valor);
}

function oauth_chave_publica_pem(array $jwk) {
    if (($jwk['kty'] ?? '') !== 'RSA' || empty($jwk['n']) || empty($jwk['e'])) {
        throw new RuntimeException('Chave publica OAuth invalida.');
    }
    $rsa = oauth_asn1(0x30, oauth_inteiro_asn1(oauth_base64url_decode($jwk['n'])) . oauth_inteiro_asn1(oauth_base64url_decode($jwk['e'])));
    $identificador = oauth_asn1(0x30, oauth_asn1(0x06, hex2bin('2a864886f70d010101')) . oauth_asn1(0x05, ''));
    $chave = oauth_asn1(0x30, $identificador . oauth_asn1(0x03, "\x00" . $rsa));
    return "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($chave), 64, "\n") . "-----END PUBLIC KEY-----\n";
}

function oauth_validar_id_token($jwt, array $config, $nonce) {
    $partes = explode('.', (string) $jwt);
    if (count($partes) !== 3) {
        throw new RuntimeException('Token de identidade invalido.');
    }
    $cabecalho = json_decode(oauth_base64url_decode($partes[0]), true, 512, JSON_THROW_ON_ERROR);
    $claims = json_decode(oauth_base64url_decode($partes[1]), true, 512, JSON_THROW_ON_ERROR);
    if (($cabecalho['alg'] ?? '') !== 'RS256' || empty($cabecalho['kid'])) {
        throw new RuntimeException('Assinatura de identidade invalida.');
    }
    $jwks = oauth_http_json($config['jwks_url']);
    $chave = null;
    foreach ($jwks['keys'] ?? [] as $jwk) {
        if (($jwk['kid'] ?? '') === $cabecalho['kid']) {
            $chave = $jwk;
            break;
        }
    }
    if (!$chave || openssl_verify($partes[0] . '.' . $partes[1], oauth_base64url_decode($partes[2]), oauth_chave_publica_pem($chave), OPENSSL_ALGO_SHA256) !== 1) {
        throw new RuntimeException('Nao foi possivel verificar a identidade.');
    }
    $audiencia = $claims['aud'] ?? '';
    $audiencia_valida = is_array($audiencia) ? in_array($config['client_id'], $audiencia, true) : hash_equals($config['client_id'], (string) $audiencia);
    if (!$audiencia_valida || !in_array($claims['iss'] ?? '', $config['issuer'], true) || (int) ($claims['exp'] ?? 0) < time() || !hash_equals($nonce, (string) ($claims['nonce'] ?? ''))) {
        throw new RuntimeException('Token de identidade expirado ou invalido.');
    }
    return $claims;
}

function oauth_garantir_tabela_identidades() {
    global $pdo;
    $pdo->exec("CREATE TABLE IF NOT EXISTS oauth_identidades (
        id int NOT NULL AUTO_INCREMENT,
        usuario_id int NOT NULL,
        provedor varchar(20) NOT NULL,
        provedor_usuario_id varchar(255) NOT NULL,
        data_criacao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY oauth_provedor_usuario (provedor, provedor_usuario_id),
        KEY oauth_usuario_id (usuario_id),
        CONSTRAINT oauth_identidades_usuario_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function oauth_obter_ou_criar_usuario($provedor, array $claims, $nome = '') {
    global $pdo;
    oauth_garantir_tabela_identidades();
    $subject = (string) ($claims['sub'] ?? '');
    if ($subject === '') {
        throw new RuntimeException('Identidade social incompleta.');
    }
    $stmt = $pdo->prepare('SELECT u.* FROM usuarios u INNER JOIN oauth_identidades o ON o.usuario_id = u.id WHERE o.provedor = ? AND o.provedor_usuario_id = ?');
    $stmt->execute([$provedor, $subject]);
    $usuario = $stmt->fetch();
    if ($usuario) {
        return $usuario;
    }
    $email = strtolower(trim((string) ($claims['email'] ?? '')));
    $email_verificado = filter_var($claims['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$email_verificado) {
        throw new RuntimeException('O provedor nao retornou um e-mail verificado.');
    }
    $usuario = obter_usuario_por_email($email);
    if (!$usuario) {
        $nome = trim($nome) ?: trim((string) ($claims['name'] ?? '')) ?: strstr($email, '@', true);
        criar_usuario($nome, $email, bin2hex(random_bytes(32)));
        $usuario = obter_usuario_por_email($email);
    }
    try {
        $stmt = $pdo->prepare('INSERT INTO oauth_identidades (usuario_id, provedor, provedor_usuario_id) VALUES (?, ?, ?)');
        $stmt->execute([$usuario['id'], $provedor, $subject]);
    } catch (PDOException $e) {
        error_log('Falha ao vincular identidade OAuth: ' . $e->getMessage());
        throw new RuntimeException('Nao foi possivel vincular a conta social.');
    }
    return $usuario;
}

function oauth_iniciar_sessao_usuario(array $usuario) {
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['admin'] = $usuario['admin'];
}
