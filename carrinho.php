<?php
session_start();
require_once 'funcoes.php';

// Verificar se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

// Processar ações do carrinho
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'remover' && isset($_POST['index'])) {
            $index = intval($_POST['index']);
            if (isset($_SESSION['carrinho'][$index])) {
                unset($_SESSION['carrinho'][$index]);
                // Reindexar o array
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
            }
        } elseif ($_POST['action'] == 'atualizar' && isset($_POST['index']) && isset($_POST['quantidade'])) {
            $index = intval($_POST['index']);
            $quantidade = intval($_POST['quantidade']);
            if (isset($_SESSION['carrinho'][$index]) && $quantidade > 0) {
                // Verificar estoque
                $produto = obter_produto_por_id($_SESSION['carrinho'][$index]['produto_id']);
                if ($produto && $quantidade <= $produto['estoque']) {
                    $_SESSION['carrinho'][$index]['quantidade'] = $quantidade;
                }
            }
        }
    }
}

// Calcular total
$total = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
}
?>
<!doctype html>
<html class="light" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>LUPIÈRE | Meu Carrinho</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&amp;family=Noto+Serif:wght@400;700&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "on-error": "#ffffff",
              "surface-container-high": "#e9e8e3",
              "surface-container": "#efeee9",
              surface: "#faf9f4",
              "on-secondary-container": "#745c00",
              "secondary-container": "#fed65b",
              "on-primary-container": "#819986",
              "on-tertiary": "#ffffff",
              "error-container": "#ffdad6",
              error: "#ba1a1a",
              background: "#faf9f4",
              "primary-fixed-dim": "#b4cdb8",
              "surface-container-low": "#f5f4ef",
              "surface-container-highest": "#e3e3de",
              "secondary-fixed-dim": "#e9c349",
              "on-tertiary-fixed-variant": "#474747",
              "outline-variant": "#c3c8c1",
              "primary-container": "#1b3022",
              "on-tertiary-container": "#939292",
              "surface-variant": "#e3e3de",
              "on-surface-variant": "#434843",
              "on-tertiary-fixed": "#1b1c1c",
              "on-secondary": "#ffffff",
              "surface-container-lowest": "#ffffff",
              tertiary: "#161717",
              "inverse-surface": "#30312e",
              "tertiary-container": "#2b2b2b",
              secondary: "#735c00",
              primary: "#061b0e",
              "on-secondary-fixed-variant": "#574500",
              "surface-bright": "#faf9f4",
              "on-background": "#1b1c19",
              "primary-fixed": "#d0e9d4",
              "tertiary-fixed": "#e4e2e1",
              "on-surface": "#1b1c19",
              "inverse-primary": "#b4cdb8",
              "on-primary": "#ffffff",
              "on-error-container": "#93000a",
              "secondary-fixed": "#ffe088",
              outline: "#737973",
              "on-primary-fixed": "#0b2013",
              "surface-tint": "#4d6453",
              "inverse-on-surface": "#f2f1ec",
              "tertiary-fixed-dim": "#c8c6c5",
              "on-primary-fixed-variant": "#364c3c",
              "on-secondary-fixed": "#241a00",
              "surface-dim": "#dbdad5",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              gutter: "24px",
              unit: "8px",
              "margin-edge": "40px",
              "container-max": "1280px",
              "section-gap": "120px",
            },
            fontFamily: {
              "body-md": ["Manrope"],
              "headline-lg": ["Noto Serif"],
              "body-lg": ["Manrope"],
              "label-caps": ["Manrope"],
              "headline-md": ["Noto Serif"],
              "headline-display": ["Noto Serif"],
            },
            fontSize: {
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
              "headline-lg": [
                "40px",
                {
                  lineHeight: "1.2",
                  letterSpacing: "-0.01em",
                  fontWeight: "400",
                },
              ],
              "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
              "label-caps": [
                "12px",
                {
                  lineHeight: "1.2",
                  letterSpacing: "0.15em",
                  fontWeight: "600",
                },
              ],
              "headline-md": ["32px", { lineHeight: "1.3", fontWeight: "400" }],
              "headline-display": [
                "64px",
                {
                  lineHeight: "1.1",
                  letterSpacing: "-0.02em",
                  fontWeight: "400",
                },
              ],
            },
          },
        },
      };
    </script>
    <style>
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
    </style>
  </head>
  <body
    class="bg-background text-on-surface font-body-md min-h-screen flex flex-col"
  >
    <!-- TopNavBar -->
    <header
      class="fixed top-0 left-0 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-20 flex items-center transition-all duration-300"
    >
      <div
        class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto"
      >
        <!-- BOTÃO MENU MOBILE -->
        <button id="menuBtn" class="lg:hidden text-[#1B3022]">
          <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- NAV DESKTOP -->
        <nav class="hidden lg:flex gap-10">
          <a class="nav-link" href="index.php">In&iacute;cio</a>
          <a class="nav-link active" href="produtos.php">Coleções</a>
          <a class="nav-link" href="acessorios.html">Acessórios</a>
          <a class="nav-link" href="sobre.php">Nossa hist&oacute;ria</a>
        </nav>

        <!-- LOGO -->
        <div
          class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]"
        >
          LUPIÈRE
        </div>

        <!-- ICONES -->
        <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
          <a href="carrinho.php" class="icon-btn">
            <span class="material-symbols-outlined">shopping_bag</span>
          </a>
          <?php if (isset($_SESSION["usuario_id"])): ?>
            <a href="perfil.php" class="icon-btn">
              <span class="material-symbols-outlined">person</span>
            </a>
          <?php else: ?>
            <a href="login.php" class="icon-btn">
              <span class="material-symbols-outlined">person</span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </header>
    <!-- MENU MOBILE -->
    <div
      id="mobileMenu"
      class="fixed top-0 left-[-100%] w-72 h-full bg-[#FAF9F4] z-50 transition-all duration-300 shadow-xl p-8 flex flex-col gap-8"
    >
      <div class="flex justify-between items-center">
        <span class="font-label-caps text-sm tracking-widest">MENU</span>
        <button id="closeMenu">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <a class="nav-link" href="index.php">In&iacute;cio</a>
      <a class="nav-link" href="produtos.php">Coleções</a>
      <a class="nav-link" href="acessorios.html">Acessórios</a>
      <a class="nav-link" href="sobre.php">Nossa hist&oacute;ria</a>
    </div>
    <main
      class="flex-grow flex items-center justify-center py-section-gap px-gutter"
    >
      <div class="max-w-container-max w-full">
        <?php if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0): ?>
          <div class="text-center py-16">
            <h2 class="font-headline-lg text-headline-lg text-primary mb-6">
              Seu carrinho está vazio
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-8">
              Adicione produtos ao seu carrinho para começar
            </p>
            <a href="produtos.php" class="bg-primary-container text-white py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300">
              Ver Produtos
            </a>
          </div>
        <?php else: ?>
          <div class="space-y-8">
            <!-- Resumo do carrinho -->
            <div class="space-y-6">
              <h2 class="font-headline-lg text-headline-lg text-primary mb-4">
                Meu Carrinho
              </h2>
              <div class="space-y-4">
                <?php foreach ($_SESSION['carrinho'] as $index => $item): ?>
                  <div class="flex flex-col md:flex-row items-start gap-6 p-6 bg-surface rounded-lg border border-outline/20">
                    <!-- Imagem do produto -->
                    <div class="w-24 h-24 md:w-32 md:h-32 flex-shrink-0">
                      <?php if (imagem_produto_disponivel($item['imagem'] ?? '')): ?>
                        <img
                          src="<?php echo escapar(imagem_produto_url($item['imagem'])); ?>"
                          alt="<?php echo escapar($item['nome']); ?>"
                          class="w-full h-full object-cover rounded-lg"
                        />
                      <?php else: ?>
                        <div class="w-full h-full bg-surface-container flex items-center justify-center rounded-lg">
                          <span class="material-symbols-outlined text-on-surface-variant/60">inventory_2</span>
                        </div>
                      <?php endif; ?>
                    </div>
                    <!-- Detalhes do produto -->
                    <div class="flex-1 space-y-3">
                      <div class="flex justify-between items-start">
                        <h3 class="font-headline-md text-headline-md text-primary">
                          <?php echo htmlspecialchars($item['nome']); ?>
                        </h3>
                        <form action="carrinho.php" method="post" class="flex items-center gap-2">
                          <input type="hidden" name="action" value="remover">
                          <input type="hidden" name="index" value="<?php echo $index; ?>">
                          <button
                            type="submit"
                            class="text-red-500 hover:text-red-700 transition-colors"
                          >
                            <span class="material-symbols-outlined">delete</span>
                          </button>
                        </form>
                      </div>
                      <p class="font-body-md text-body-md text-on-surface-variant">
                        R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> x
                        <?php echo $item['quantidade']; ?>
                      </p>
                      <form action="carrinho.php" method="post" class="flex items-center gap-3 mt-2">
                        <input type="hidden" name="action" value="atualizar">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input
                          type="number"
                          name="quantidade"
                          value="<?php echo $item['quantidade']; ?>"
                          min="1"
                          class="w-20 px-3 py-2 border border-outline/30 rounded-md focus:border-primary focus:outline-none"
                        >
                        <button
                          type="submit"
                          class="px-4 py-2 bg-outline/20 text-outline hover:bg-outline/30 rounded-md transition-colors"
                        >
                          Atualizar
                        </button>
                      </form>
                      <p class="font-body-md text-body-md">
                        Subtotal: R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?>
                      </p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Total e checkout -->
            <div class="border-t border-outline/20 pt-8">
              <div class="flex justify-between items-center mb-6">
                <span class="font-label-caps text-label-caps text-on-surface-variant">Total:</span>
                <span class="font-headline-md text-headline-md text-primary">
                  R$ <?php echo number_format($total, 2, ',', '.'); ?>
                </span>
              </div>
              <div class="space-y-4">
                <a
                  href="produtos.php"
                  class="w-full border border-outline/30 text-primary py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-surface-container-low transition-all duration-300"
                >
                  Continuar Comprando
                </a>
                <a
                  href="checkout.php"
                  class="w-full bg-primary-container text-white py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                >
                  Finalizar Compra
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </main>
    <!-- Footer -->
    <footer
      class="mt-auto w-full border-t border-outline/20 bg-surface-container flex items-center justify-center py-8"
    >
      <div class="text-center text-on-surface-variant/60 font-body-md">
        &copy; <?php echo date("Y"); ?> LUPIÈRE. Todos os direitos reservados.
      </div>
    </footer>

    <script>
      document.getElementById("menuBtn").addEventListener("click", function () {
        document.getElementById("mobileMenu").classList.remove("left-[-100%]");
        document.getElementById("mobileMenu").classList.add("left-0");
      });

      document.getElementById("closeMenu").addEventListener("click", function () {
        document.getElementById("mobileMenu").classList.remove("left-0");
        document.getElementById("mobileMenu").classList.add("left-[-100%]");
      });
    </script>
  </body>
</html>
