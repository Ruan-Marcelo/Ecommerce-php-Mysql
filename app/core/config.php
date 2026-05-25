<?php

ini_set('display_errors', '0');
ini_set('log_errors', '1');

if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');
    header('Cross-Origin-Resource-Policy: same-origin');
}

$sName = getenv('DB_HOST') ?: 'localhost';
$uName = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'lupiere';
$db_port = getenv('DB_PORT') ?: '3307';

try {
    $pdo = new PDO(
        "mysql:host=$sName;port=$db_port;dbname=$db_name;charset=utf8mb4",
        $uName,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Erro de conexão ao banco: " . $e->getMessage());
    http_response_code(500);
    die("Erro interno. Tente novamente mais tarde.");
}
