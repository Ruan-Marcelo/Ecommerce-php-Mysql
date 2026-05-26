<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

if (($_SERVER["REQUEST_METHOD"] ?? '') == "POST") {

    $nome = trim($_POST["full_name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $telefone = trim($_POST["telefone"] ?? '');
    $senha = $_POST["password"] ?? '';
    $inscrever_email = isset($_POST['newsletter']);

    // Validação
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {

        // Verifica se email já existe
        if (obter_usuario_por_email($email)) {

            $erro = "Este email já está cadastrado.";

        } else {

            // Cria usuário usando função do funcoes.php
            $criado = criar_usuario(
                $nome,
                $email,
                $senha,
                $telefone
            );

            if ($criado) {
                $usuario_criado = obter_usuario_por_email($email);
                if ($inscrever_email) {
                    garantir_inscricao_email($email, $nome, $usuario_criado['id'] ?? null, 'cadastro', 1);
                }

                $sucesso = "Conta criada com sucesso!";

            } else {

                $erro = "Erro ao criar conta.";

            }
        }
    }
}
?>
<!doctype html>

<html class="light" lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>LUPIÈRE | Registrar</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&family=Noto+Serif:wght@400;700&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        background: "#faf9f4",
                        primary: "#061b0e",
                        secondary: "#735c00",
                        outline: "#737973",
                        "outline-variant": "#c3c8c1",
                        "primary-container": "#1b3022",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f5f4ef",
                        "on-surface": "#1b1c19",
                        "on-surface-variant": "#434843",
                    },

                    fontFamily: {
                        body: ["Manrope"],
                        headline: ["Noto Serif"],
                    },
                },
            },
        };
    </script>

    <style>

        body {
            font-family: 'Manrope', sans-serif;
        }

        .headline {
            font-family: 'Noto Serif', serif;
        }

        .material-symbols-outlined {
            font-variation-settings:
                "FILL" 0,
                "wght" 300,
                "GRAD" 0,
                "opsz" 24;
        }

        .form-input-bespoke {
            border: none;
            border-bottom: 1px solid rgba(27, 48, 34, 0.2);
            background: transparent;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .form-input-bespoke:focus {
            border-bottom: 1px solid #735c00;
            box-shadow: none;
            outline: none;
        }

        .nav-link {
            position: relative;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 12px;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: #735c00;
        }

        .icon-btn:hover {
            color: #735c00;
            transition: 0.3s;
        }

    </style>
</head>

<body class="bg-background text-on-surface min-h-screen flex flex-col overflow-x-hidden">

<!-- HEADER -->
<header class="fixed top-0 left-0 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-20 flex items-center">

    <div class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto">

        <!-- MENU MOBILE -->
        <button id="menuBtn" class="lg:hidden text-[#1B3022]">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- NAV -->
        <nav class="hidden lg:flex gap-10">

            <a class="nav-link" href="index.php">Inicio</a>

            <a class="nav-link" href="produtos.php">Coleções</a>

            <a class="nav-link" href="acessorios.php">Acessórios</a>

            <a class="nav-link" href="sobre.php">Nossa história</a>

        </nav>

        <!-- LOGO -->
        <div class="text-xl md:text-2xl headline tracking-[0.4em] text-[#1B3022]">
            LUPIÈRE
        </div>

        <!-- ICONES -->
        <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">

            <a href="carrinho.php" class="icon-btn">
                <span class="material-symbols-outlined">shopping_bag</span>
            </a>

            <a href="login.php" class="icon-btn">
                <span class="material-symbols-outlined">person</span>
            </a>

        </div>

    </div>

</header>

<!-- MENU MOBILE -->
<div
    id="mobileMenu"
    class="fixed top-0 left-[-100%] w-72 h-full bg-[#FAF9F4] z-50 transition-all duration-300 shadow-xl p-8 flex flex-col gap-8"
>

    <div class="flex justify-between items-center">

        <span class="uppercase tracking-widest text-sm">
            Menu
        </span>

        <button id="closeMenu">
            <span class="material-symbols-outlined">close</span>
        </button>

    </div>

    <a class="nav-link" href="index.php">Inicio</a>
    <a class="nav-link" href="produtos.php">Coleções</a>
    <a class="nav-link" href="acessorios.php">Acessórios</a>
    <a class="nav-link" href="sobre.php">Nossa história</a>

</div>

<!-- MAIN -->
<main class="flex-grow flex items-center justify-center py-40 px-6">

    <div class="max-w-7xl w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

        <!-- IMAGEM -->
        <div class="hidden md:block relative h-[700px]">

            <div class="absolute inset-0 bg-primary-container/5 mix-blend-multiply z-10"></div>

            <img
                alt="Bespoke Tailoring"
                class="w-full h-full object-cover"
                src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1200&auto=format&fit=crop"
            />

            <div class="absolute bottom-12 left-12 z-20 text-white">

                <p class="uppercase tracking-[0.2em] mb-4 text-sm">
                    Established 1984
                </p>

                <p class="headline text-3xl italic max-w-sm">
                    "True elegance is the absence of noise."
                </p>

            </div>

        </div>

        <!-- FORM -->
        <div class="flex flex-col justify-center max-w-md mx-auto md:mx-0 w-full">

            <header class="mb-12">

                <h2 class="headline text-5xl text-primary mb-2">
                    Junte-se ao Atelier
                </h2>

                <p class="text-lg text-on-surface-variant italic">
                    Entre no mundo de Lupière.
                </p>

            </header>

            <!-- ALERTAS -->
            <?php if (isset($sucesso)): ?>

                <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                    <?php echo $sucesso; ?>
                </div>

            <?php endif; ?>

            <?php if (isset($erro)): ?>

                <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                    <?php echo $erro; ?>
                </div>

            <?php endif; ?>

            <!-- FORMULARIO -->
            <form class="space-y-8" method="POST">

                <div class="space-y-6">

                    <!-- NOME -->
                    <div>

                        <label class="block uppercase text-xs tracking-widest text-outline mb-2">
                            Nome Completo
                        </label>

                        <input
                            class="w-full form-input-bespoke py-3"
                            name="full_name"
                            placeholder="Alexander Sterling"
                            type="text"
                            required
                        />

                    </div>

                    <!-- EMAIL -->
                    <div>

                        <label class="block uppercase text-xs tracking-widest text-outline mb-2">
                            Email
                        </label>

                        <input
                            class="w-full form-input-bespoke py-3"
                            name="email"
                            placeholder="email@exemplo.com"
                            type="email"
                            required
                        />

                    </div>

                    <!-- TELEFONE -->
                    <div>

                        <label class="block uppercase text-xs tracking-widest text-outline mb-2">
                            Telefone
                        </label>

                        <input
                            class="w-full form-input-bespoke py-3"
                            name="telefone"
                            placeholder="(16) 99999-9999"
                            type="tel"
                            required
                        />

                    </div>

                    <!-- SENHA -->
                    <div>

                        <label class="block uppercase text-xs tracking-widest text-outline mb-2">
                            Senha
                        </label>

                        <input
                            class="w-full form-input-bespoke py-3"
                            name="password"
                            placeholder="••••••••"
                            type="password"
                            required
                        />

                    </div>

                </div>

                <!-- CHECKBOX -->
                <div class="flex items-start gap-3 pt-2">

                    <input
                        class="mt-1"
                        id="newsletter"
                        name="newsletter"
                        type="checkbox"
                    />

                    <label class="text-sm text-on-surface-variant" for="newsletter">
                        Assine a newsletter exclusiva para receber novidades e catálogos.
                    </label>

                </div>

                <!-- BOTÃO -->
                <div class="pt-8 space-y-6">

                    <button
                        class="w-full bg-primary-container text-white py-5 uppercase tracking-widest hover:bg-primary transition-all duration-300"
                        type="submit"
                    >
                        Criar Conta
                    </button>

                    <div class="flex items-center justify-between">

                        <span class="h-px bg-outline-variant/30 flex-grow"></span>

                        <span class="px-4 uppercase text-xs text-outline">
                            Ou
                        </span>

                        <span class="h-px bg-outline-variant/30 flex-grow"></span>

                    </div>

                    <!-- GOOGLE -->
                    <button
                        class="w-full border border-outline/30 text-primary py-4 uppercase tracking-widest hover:bg-surface-container-low transition-all duration-300 flex items-center justify-center gap-3"
                        type="button"
                    >

                        <img
                            src="https://developers.google.com/identity/images/g-logo.png"
                            alt="Google"
                            class="w-5 h-5"
                        />

                        Continue com Google

                    </button>

                </div>

            </form>

            <!-- LOGIN -->
            <p class="mt-12 text-center text-sm text-on-surface-variant">

                Já possui conta?

                <a
                    class="text-secondary font-semibold hover:underline"
                    href="login.php"
                >
                    Entrar
                </a>

            </p>

        </div>

    </div>

</main>

<!-- FOOTER -->
<footer class="bg-[#FAF9F4] w-full py-20 px-8 md:px-16 border-t border-[#1B3022]/10">

    <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">

        <div>

            <div class="text-2xl headline tracking-[0.3em] text-[#1B3022] uppercase mb-8">
                LUPIÈRE
            </div>

            <p class="text-[#1B3022]/60 max-w-xs">
                Elegância clássica para o homem contemporâneo.
            </p>

        </div>

        <div>

            <h4 class="uppercase tracking-widest mb-4 text-sm">
                Explorar
            </h4>

            <div class="flex flex-col gap-3">

                <a href="index.php">Inicio</a>

                <a href="produtos.php">Coleções</a>

                <a href="acessorios.php">Acessórios</a>

            </div>

        </div>

        <div>

            <h4 class="uppercase tracking-widest mb-4 text-sm">
                Atendimento
            </h4>

            <div class="flex flex-col gap-3">

                <a href="#">Envio & Devoluções</a>

                <a href="#">Política de Privacidade</a>

            </div>

        </div>

        <div>

            <h4 class="uppercase tracking-widest mb-4 text-sm">
                Atelier
            </h4>

            <p class="text-[#1B3022]/60">
                Avenida da Liberdade, 110<br>
                Lisboa, Portugal
            </p>

        </div>

    </div>

</footer>

<!-- SCRIPT MENU MOBILE -->
<script>

    const menuBtn = document.getElementById("menuBtn");
    const closeMenu = document.getElementById("closeMenu");
    const mobileMenu = document.getElementById("mobileMenu");

    menuBtn.addEventListener("click", () => {
        mobileMenu.style.left = "0";
    });

    closeMenu.addEventListener("click", () => {
        mobileMenu.style.left = "-100%";
    });

</script>

</body>
</html>
