<?php
session_start();
require_once 'funcoes.php';

// Buscar todos os produtos
$produtos = obter_produtos();
?>
<!doctype html>
<html class="light" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>LUPIÈRE | Coleções</title>
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
              "on-primary-container": "#819986",
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
      <a class="nav-link active" href="produtos.php">Coleções</a>
      <a class="nav-link" href="acessorios.html">Acessórios</a>
      <a class="nav-link" href="sobre.php">Nossa hist&oacute;ria</a>
    </div>
    <main
      class="flex-grow flex items-center justify-center py-section-gap px-gutter"
    >
      <div
        class="max-w-container-max w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center"
      >
        <!-- Branding/Image Side -->
        <div class="hidden md:block relative h-[700px]">
          <div
            class="absolute inset-0 bg-primary-container/5 mix-blend-multiply z-10"
          ></div>
          <img
            alt="Bespoke Tailoring"
            class="w-full h-full object-cover"
            data-alt="A cinematic, high-contrast close-up of a master tailor's hands working on a dark forest green wool blazer. The lighting is soft and editorial, highlighting the intricate textures of the premium fabric and the gleam of a gold needle. The scene is set in a minimalist, sun-drenched atelier with pristine white walls and natural wood accents. The overall mood is one of quiet luxury, tradition, and meticulous craftsmanship, reflecting the Lupière brand identity."
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAP5RPjGWcwf_mephUfnIzNbEy2-oq3MjBvNWOQH2lsATXiGMBG-UwyMeKbWcZgaBNLUGZQpf-g-m7z7rHknez8VBLaNnv1Hczya4b-M-N0qPzF4yr7inq9XI15RcsLWeM0bA9y2y3ruu_fg4B3a5AcCu_XVYjAcKzylb_NBv8MIhyVy9I2H5V6_pHesplt-lE_bdZ4Iuzym2f1798HhvmXBtNfL-0AsZTKpbd8A4Hd6p3NPx2_jlzBHRQT3De7r-4S1fHchXOMpmgR"
          />
          <div class="absolute bottom-12 left-12 z-20 text-white">
            <p
              class="font-label-caps text-label-caps text-surface uppercase tracking-[0.2em] mb-4"
            >
              Established 1984
            </p>
            <p class="font-headline-md text-headline-md italic max-w-sm">
              "True elegance is the absence of noise."
            </p>
          </div>
        </div>
        <!-- Products Listing Side -->
        <div
          class="flex flex-col justify-center max-w-md mx-auto md:mx-0 w-full space-y-12"
        >
          <header class="mb-6">
            <h2 class="font-headline-lg text-headline-lg text-primary mb-2">
              Coleções
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant italic">
              Explore nossa seleção de peças
            </p>
          </header>
          <?php if (empty($produtos)): ?>
            <p class="text-center text-on-surface-variant">Nenhum produto encontrado.</p>
          <?php else: ?>
            <div class="space-y-8">
              <?php foreach ($produtos as $produto): ?>
                <div
                  class="flex flex-col items-center gap-6 p-8 bg-surface rounded-lg border border-outline/20"
                >
                  <?php if (imagem_produto_disponivel($produto['imagem'] ?? '')): ?>
                    <img
                      src="<?php echo escapar(imagem_produto_url($produto['imagem'])); ?>"
                      alt="<?php echo escapar($produto['nome']); ?>"
                      class="w-full h-[300px] object-cover"
                    />
                  <?php else: ?>
                    <div class="w-full h-[300px] bg-surface-container flex items-center justify-center">
                      <span class="material-symbols-outlined text-on-surface-variant/60 text-5xl">inventory_2</span>
                    </div>
                  <?php endif; ?>
                  <div class="text-center space-y-4">
                    <h3 class="font-headline-md text-headline-md text-primary">
                      <?php echo htmlspecialchars($produto['nome']); ?>
                    </h3>
                    <p class="font-body-lg text-body-lg text-on-surface-variant">
                      <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?>
                    </p>
                    <div class="flex items-center gap-4 text-primary font-label-caps">
                      <span>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                      <?php if ($produto['estoque'] > 0): ?>
                        <span class="text-green-500">Em estoque</span>
                      <?php else: ?>
                        <span class="text-red-500">Esgotado</span>
                      <?php endif; ?>
                    </div>
                    <div class="flex justify-center">
                      <?php if (isset($_SESSION["usuario_id"]) && $produto['estoque'] > 0): ?>
                        <form action="adicionar_carrinho.php" method="post" class="flex items-center gap-2">
                          <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                          <button
                            type="submit"
                            class="bg-primary-container text-white py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                          >
                            Adicionar ao Carrinho
                          </button>
                        </form>
                      <?php elseif (!isset($_SESSION["usuario_id"])): ?>
                        <a
                          href="login.php"
                          class="border border-outline/30 text-primary py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-surface-container-low transition-all duration-300"
                        >
                          Faça login para comprar
                        </a>
                      <?php else: ?>
                        <span class="text-red-500 font-label-caps">Indisponível</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
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
