<?php
session_start();

require_once __DIR__ . '/app/core/funcoes.php';

// REDIRECIONA SE JÃ ESTIVER LOGADO
if (isset($_SESSION["usuario_id"])) {
    header("Location: " . (!empty($_SESSION["admin"]) ? "admin/index.php" : "perfil.php"));
    exit();
}

// PROCESSA LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha = trim($_POST["password"]);

    if (!empty($email) && !empty($senha)) {

        $usuario = obter_usuario_por_email($email);

        if ($usuario && password_verify($senha, $usuario["senha"])) {

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_email"] = $usuario["email"];
            $_SESSION["admin"] = $usuario["admin"];

            header("Location: " . (!empty($usuario["admin"]) ? "admin/index.php" : "perfil.php"));
            exit();

        } else {

            $erro = "Email ou senha invÃ¡lidos.";

        }

    } else {

        $erro = "Preencha todos os campos.";

    }
}
?>

<!doctype html>

<html class="light" lang="pt-BR">

<head>

    <meta charset="utf-8" />

    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>LUPIÃˆRE | Login</title>

    <meta name="description" content="Acesse sua conta LupiÃ¨re. ElegÃ¢ncia, sofisticaÃ§Ã£o e alfaiataria premium." />

    <link rel="icon" href="favicon.ico" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:wght@400;700&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    />

    <script>

        tailwind.config = {

            darkMode: 'class',

            theme: {

                extend: {

                    colors: {

                        primary: '#1B3022',
                        secondary: '#735C00',
                        background: '#FAF9F4',

                    }

                }

            }

        }

    </script>

    <style>

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', sans-serif;
        }

        .title-font {
            font-family: 'Noto Serif', serif;
        }

        .material-symbols-outlined {

            
        font-family: 'Material Symbols Outlined' !important;
        font-style: normal;
        font-weight: normal;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
font-variation-settings:
            'FILL' 0,
            'wght' 300,
            'GRAD' 0,
            'opsz' 24;

        }

        .nav-link {

            position: relative;
            text-transform: uppercase;
            letter-spacing: .18em;
            font-size: 12px;
            transition: .3s;

        }

        .nav-link::after {

            content: '';
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0;
            height: 1px;
            background: #1B3022;
            transition: .3s;

        }

        .nav-link:hover::after {

            width: 100%;

        }

        .icon-btn {

            transition: .3s;

        }

        .icon-btn:hover {

            opacity: .65;
            transform: translateY(-1px);

        }

        .login-card {

            backdrop-filter: blur(10px);

        }

        .fade-up {

            animation: fadeUp .8s ease forwards;

        }

        @keyframes fadeUp {

            from {

                opacity: 0;
                transform: translateY(25px);

            }

            to {

                opacity: 1;
                transform: translateY(0);

            }

        }

        .wolf-watermark {

            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: .03;
            pointer-events: none;
            z-index: 0;

        }

        .custom-input:focus {

            box-shadow: none !important;

        }

    </style>

</head>

<body class="bg-background text-primary min-h-screen flex flex-col overflow-x-hidden">

<!-- HEADER -->
<header class="fixed top-0 left-0 right-0 z-50 bg-[#FAF9F4]/90 backdrop-blur-md border-b border-[#1B3022]/10 h-20 flex items-center">

    <div class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto">

        <!-- MOBILE BTN -->
        <button id="menuBtn" class="lg:hidden">

            <span class="notranslate material-symbols-outlined" translate="no">
                menu
            </span>

        </button>

        <!-- NAV -->
        <nav class="hidden lg:flex gap-10">

            <a href="index.php" class="nav-link">
                Inicio
            </a>

            <a href="produtos.php" class="nav-link">
                ColeÃ§Ãµes
            </a>

            <a href="acessorios.php" class="nav-link">
                AcessÃ³rios
            </a>

            <a href="sobre.php" class="nav-link">
                Nossa histÃ³ria
            </a>

        </nav>

        <!-- LOGO -->
        <a href="index.php"
           class="text-xl md:text-2xl title-font tracking-[0.4em] uppercase">

            LUPIÃˆRE

        </a>

        <!-- ICONES -->
        <div class="flex items-center gap-6">

            <a href="carrinho.php" class="icon-btn">

                <span class="notranslate material-symbols-outlined" translate="no">
                    shopping_bag
                </span>

            </a>

            <a href="login.php" class="icon-btn">

                <span class="notranslate material-symbols-outlined" translate="no">
                    person
                </span>

            </a>

        </div>

    </div>

</header>

<!-- MENU MOBILE -->
<div id="mobileMenu"
     class="fixed top-0 left-[-100%] w-72 h-full bg-[#FAF9F4] z-50 transition-all duration-300 shadow-2xl p-8 flex flex-col gap-8">

    <div class="flex justify-between items-center">

        <span class="uppercase tracking-[0.3em] text-xs">
            Menu
        </span>

        <button id="closeMenu">

            <span class="notranslate material-symbols-outlined" translate="no">
                close
            </span>

        </button>

    </div>

    <a href="index.php" class="nav-link">
        Inicio
    </a>

    <a href="produtos.php" class="nav-link">
        ColeÃ§Ãµes
    </a>

    <a href="acessorios.php" class="nav-link">
        AcessÃ³rios
    </a>

    <a href="sobre.php" class="nav-link">
        Nossa histÃ³ria
    </a>

</div>

<!-- MAIN -->
<main class="flex-grow flex items-center justify-center px-6 py-32 relative">

    <!-- WATERMARK -->
    <div class="wolf-watermark">

        <span class="notranslate material-symbols-outlined text-[35rem]" translate="no">
            auto_awesome
        </span>

    </div>

    <!-- LOGIN CARD -->
    <div class="relative z-10 w-full max-w-[500px] fade-up">

        <!-- TITLE -->
        <div class="text-center mb-12">

            <h1 class="title-font text-5xl md:text-6xl mb-4">
                Bem-vindo de volta
            </h1>

            <p class="uppercase tracking-[0.3em] text-xs text-[#1B3022]/50">

                Acesse sua conta Lupiere.

            </p>

        </div>

        <!-- CARD -->
        <div class="login-card bg-white/90 border border-[#1B3022]/10 p-10 md:p-12 shadow-xl">

            <!-- ERRO -->
            <?php if (isset($erro)): ?>

                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-lg text-sm">

                    <?php echo escapar($erro); ?>

                </div>

            <?php endif; ?>

            <!-- FORM -->
            <form method="POST" class="space-y-8">

                <!-- EMAIL -->
                <div class="space-y-2">

                    <label class="uppercase tracking-[0.2em] text-[11px] text-[#1B3022]/60">

                        E-mail

                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="custom-input w-full bg-transparent border-0 border-b border-[#1B3022]/20 py-4 focus:border-secondary transition-all placeholder:text-[#1B3022]/30"
                    />

                </div>

                <!-- SENHA -->
                <div class="space-y-2">

                    <div class="flex justify-between items-center">

                        <label class="uppercase tracking-[0.2em] text-[11px] text-[#1B3022]/60">

                            Senha

                        </label>

                        <a href="#"
                           class="text-[10px] uppercase tracking-[0.2em] hover:underline">

                            Esqueceu a senha?

                        </a>

                    </div>

                    <div class="relative">

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢"
                            class="custom-input w-full bg-transparent border-0 border-b border-[#1B3022]/20 py-4 pr-10 focus:border-secondary transition-all placeholder:text-[#1B3022]/30"
                        />

                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-0 top-1/2 -translate-y-1/2 text-[#1B3022]/50 hover:text-[#1B3022] transition">

                            <span class="notranslate material-symbols-outlined" translate="no">

                                visibility

                            </span>

                        </button>

                    </div>

                </div>

                <!-- REMEMBER -->
                <div class="flex items-center justify-between text-sm">

                    <label class="flex items-center gap-2 cursor-pointer">

                        <input type="checkbox" class="rounded border-[#1B3022]/20">

                        <span class="text-[#1B3022]/60">
                            Lembrar de mim
                        </span>

                    </label>

                </div>

                <!-- BTN -->
                <button
                    type="submit"
                    id="loginBtn"
                    class="w-full bg-primary text-white py-5 uppercase tracking-[0.25em] text-xs hover:opacity-90 active:scale-[0.99] transition-all duration-300">

                    Entrar

                </button>

            </form>

            <!-- DIVIDER -->
            <div class="relative my-10">

                <div class="absolute inset-0 flex items-center">

                    <span class="w-full border-t border-[#1B3022]/10"></span>

                </div>

                <div class="relative flex justify-center">

                    <span class="bg-white px-4 uppercase tracking-[0.25em] text-[10px] text-[#1B3022]/40">

                        Ou continue com

                    </span>

                </div>

            </div>

            <!-- SOCIAL -->
            <div class="grid grid-cols-2 gap-4">

                <!-- GOOGLE -->
                <button
                    type="button"
                    class="border border-[#1B3022]/10 py-4 uppercase tracking-[0.15em] text-xs hover:bg-[#F5F5F5] transition flex items-center justify-center gap-3">

                    <img
                        src="https://developers.google.com/identity/images/g-logo.png"
                        alt="Google"
                        class="w-5 h-5"
                    />

                    Google

                </button>

                <!-- APPLE -->
                <button
                    type="button"
                    class="border border-[#1B3022]/10 py-4 uppercase tracking-[0.15em] text-xs hover:bg-[#F5F5F5] transition flex items-center justify-center gap-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="currentColor"
                         class="w-5 h-5">

                        <path d="M16.365 1.43c0 1.14-.463 2.21-1.24 3.02-.81.84-2.14 1.48-3.28 1.39-.14-1.12.41-2.25 1.16-3.06.77-.84 2.09-1.45 3.36-1.35zm4.3 16.11c-.68 1.55-1.5 2.98-2.76 2.99-1.23.02-1.63-.79-3.05-.79-1.42 0-1.87.77-3.02.81-1.22.05-2.16-1.6-2.86-3.14-1.38-3.02-2.44-8.55 1.92-11.06 1.08-.63 2.3-.99 3.58-.99 1.4 0 2.62.9 3.42.9.8 0 2.3-1.11 3.88-.95.66.03 2.5.27 3.69 2.01-.1.06-2.2 1.28-2.18 3.82.02 3.03 2.66 4.03 2.69 4.05-.02.07-.42 1.44-1.33 2.35z"/>

                    </svg>

                    Apple

                </button>

            </div>

            <!-- REGISTER -->
            <div class="mt-10 text-center">

                <p class="text-sm text-[#1B3022]/60">

                    NÃ£o possui uma conta?

                    <a href="registrar.php"
                       class="font-semibold hover:underline ml-1">

                        Criar conta

                    </a>

                </p>

            </div>

        </div>

    </div>

</main>

<!-- FOOTER -->
<footer class="bg-[#FAF9F4] border-t border-[#1B3022]/10 py-16 px-6 md:px-16">

    <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between gap-10">

        <div>

            <div class="title-font text-2xl tracking-[0.3em] uppercase mb-5">

                LUPIÃˆRE

            </div>

            <p class="text-[#1B3022]/60 max-w-sm leading-relaxed">

                Elevando a alfaiataria clÃ¡ssica para o homem contemporÃ¢neo.
                SofisticaÃ§Ã£o, tradiÃ§Ã£o e personalidade.

            </p>

        </div>

        <div class="text-[10px] uppercase tracking-[0.25em] text-[#1B3022]/40 flex items-end">

            &copy; 2026 LUPI&Egrave;RE ALFAIATARIA

        </div>

    </div>

</footer>

<!-- SCRIPT -->
<script>

    // MENU MOBILE
    const menuBtn = document.getElementById('menuBtn');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');

    menuBtn.addEventListener('click', () => {

        mobileMenu.style.left = '0';

    });

    closeMenu.addEventListener('click', () => {

        mobileMenu.style.left = '-100%';

    });

    // MOSTRAR SENHA
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', () => {

        if (password.type === 'password') {

            password.type = 'text';

            togglePassword.innerHTML = `
                <span class="notranslate material-symbols-outlined" translate="no">
                    visibility_off
                </span>
            `;

        } else {

            password.type = 'password';

            togglePassword.innerHTML = `
                <span class="notranslate material-symbols-outlined" translate="no">
                    visibility
                </span>
            `;

        }

    });

    // LOADING BUTTON
    const form = document.querySelector('form');
    const loginBtn = document.getElementById('loginBtn');

    form.addEventListener('submit', () => {

        loginBtn.innerHTML = 'ENTRANDO...';
        loginBtn.disabled = true;

    });

</script>

</body>

</html>
