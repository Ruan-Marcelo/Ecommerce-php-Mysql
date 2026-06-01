<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar inputs
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // Validação básica
    $errors = [];

    if (empty($nome)) {
        $errors[] = 'Nome é obrigatório';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail válido é obrigatório';
    }

    if (empty($mensagem)) {
        $errors[] = 'Mensagem é obrigatória';
    }

    if (empty($errors)) {
        // Aqui você poderia enviar email ou salvar no banco
        // Por enquanto, vamos apenas mostrar mensagem de sucesso
        $_SESSION['contato_sucesso'] = 'Sua mensagem foi enviada com sucesso! Nossa equipe entrará em contato em até 24 horas.';
        header('Location: contato.php');
        exit();
    } else {
        $_SESSION['contato_erro'] = implode('<br>', $errors);
        header('Location: contato.php');
        exit();
    }
} else {
    header('Location: contato.php');
    exit();
}
?>
