<!doctype html>

<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>LUPIÈRE</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&amp;family=Noto+Serif:wght@400&amp;display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="public/assets/css/style.css" />
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              surface: "#faf9f4",
              "tertiary-container": "#2b2b2b",
              "secondary-fixed-dim": "#e9c349",
              "tertiary-fixed": "#e4e2e1",
              "inverse-surface": "#30312e",
              "on-surface": "#1b1c19",
              "primary-container": "#1b3022",
              "on-error-container": "#93000a",
              "inverse-on-surface": "#f2f1ec",
              "on-primary-fixed": "#0b2013",
              "surface-container-low": "#f5f4ef",
              "surface-container-lowest": "#ffffff",
              "surface-tint": "#4d6453",
              "on-secondary-fixed-variant": "#574500",
              "on-secondary-fixed": "#241a00",
              "secondary-container": "#fed65b",
              "on-tertiary-fixed": "#1b1c1c",
              primary: "#061b0e",
              "inverse-primary": "#b4cdb8",
              "on-error": "#ffffff",
              "on-tertiary": "#ffffff",
              "on-primary": "#ffffff",
              tertiary: "#161717",
              "on-surface-variant": "#434843",
              outline: "#737973",
              "on-primary-container": "#819986",
              "error-container": "#ffdad6",
              "on-primary-fixed-variant": "#364c3c",
              "secondary-fixed": "#ffe088",
              "on-secondary": "#ffffff",
              "surface-container-highest": "#e3e3de",
              "on-background": "#1b1c19",
              "primary-fixed": "#d0e9d4",
              "on-secondary-container": "#745c00",
              "surface-container": "#efeee9",
              "tertiary-fixed-dim": "#c8c6c5",
              background: "#faf9f4",
              "surface-dim": "#dbdad5",
              "surface-bright": "#faf9f4",
              "on-tertiary-fixed-variant": "#474747",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              "section-gap": "120px",
              gutter: "24px",
              "container-max": "1440px",
              "margin-edge": "40px",
              unit: "8px",
            },
            fontFamily: {
              "body-lg": ["Manrope"],
              "headline-lg": ["Noto Serif"],
              "label-caps": ["Manrope"],
              "headline-md": ["Noto Serif"],
              "headline-display": ["Noto Serif"],
              "body-md": ["Manrope"],
            },
            fontSize: {
              "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
              "headline-lg": [
                "40px",
                {
                  lineHeight: "1.2",
                  letterSpacing: "-0.01em",
                  fontWeight: "400",
                },
              ],
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
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
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
        display: inline-block;
      }
      body {
        background-color: #faf9f4;
      }
    </style>
  </head>
  <body class="bg-surface text-on-surface antialiased">
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
          <a class="nav-link" href="index.php">Inicio</a>
          <a class="nav-link" href="produtos.php">Coleções</a>
          <a class="nav-link" href="acessorios.php">Acessórios</a>
          <a class="nav-link" href="sobre.php">Nossa história</a>
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
        <span class="font-label-caps text-sm tracking-widest">MENU</span>
        <button id="closeMenu">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <a class="nav-link" href="index.php">Inicio</a>
      <a class="nav-link" href="produtos.php">Coleções</a>
      <a class="nav-link" href="acessorios.php">Acessórios</a>
      <a class="nav-link" href="sobre.php">Nossa história</a>
    </div>

    <!-- OVERLAY -->
    <div
      id="overlay"
      class="fixed inset-0 bg-black/30 opacity-0 pointer-events-none transition-all duration-300 z-40"
    ></div>
    <main>
      <!-- Hero Section -->
      <section class="relative h-screen w-full overflow-hidden">
        <div class="absolute inset-0 z-0">
          <img
            class="w-full h-full object-cover grayscale-[20%]"
            data-alt="A cinematic portrait of a sophisticated man wearing a bespoke casual tailored linen jacket and trousers. He is standing against a minimalist architectural backdrop with soft morning sunlight casting elegant shadows. The atmosphere is quiet, luxurious, and authoritative, reflecting the premium tailoring brand identity with high-contrast textures."
            src="O equilíbrio perfeito entre conforto e elegância.A Polo Piquet Premium Lupière foi criada para m.jpg"
          />
          <div class="absolute inset-0 bg-primary/20 mix-blend-multiply"></div>
        </div>
        <div
          class="relative z-10 h-full flex flex-col justify-end pb-32 px-16 max-w-[1440px] mx-auto"
        >
          <div class="max-w-2xl">
            <h1
              class="font-headline-display text-headline-display text-surface mb-6"
            >
              Elegância com Personalidade
            </h1>
            <p
              class="font-body-lg text-body-lg text-surface/90 mb-10 border-l-2 border-secondary-fixed-dim pl-6"
            >
              Peças feitas para quem impõe presença.
            </p>
            <a
              href="produtos.php"
              class="bg-primary-container text-surface px-12 py-5 font-label-caps text-label-caps uppercase tracking-[0.2em] hover:bg-on-primary-container transition-all duration-500 inline-block text-center"
            >
              Explorar Coleção
            </a>
          </div>
        </div>
      </section>
      <!-- Section 1: A Essência -->
      <section
        class="py-section-gap px-16 max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-12 gap-gutter items-center"
      >
        <div class="md:col-span-5">
          <span
            class="font-label-caps text-label-caps text-secondary block mb-8"
            >MANIFESTO</span
          >
          <h2 class="font-headline-lg text-headline-lg text-primary mb-12">
            A Essência
          </h2>
        </div>
        <div class="md:col-span-6 md:col-start-7">
          <p
            class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed mb-8"
          >
            A Lupière nasceu do desejo de resgatar o rigor da alfaiataria
            clássica para o dinamismo do homem contemporâneo. Não criamos apenas
            roupas; moldamos armaduras de confiança.
          </p>
          <p class="font-body-md text-body-md text-on-surface-variant/80">
            Cada costura é um diálogo entre tradição e subversão. Nossa
            identidade é forte, autêntica e inegociável. Para nós, o luxo não
            grita; ele se faz notar pela precisão do corte e pela nobreza da
            matéria-prima.
          </p>
          <div class="mt-12 h-px w-24 bg-secondary-fixed-dim"></div>
        </div>
      </section>
      <!-- Section 2: Coleções (Bento Grid Style) -->
      <section class="py-section-gap px-8 md:px-16 bg-surface-container-low">
        <div class="max-w-[1440px] mx-auto">
          <div class="flex justify-between items-end mb-20">
            <div>
              <span
                class="font-label-caps text-label-caps text-secondary block mb-4"
                >CURADORIA</span
              >
              <h2 class="font-headline-lg text-headline-lg text-primary">
                Coleções
              </h2>
            </div>
            <a
              class="font-label-caps text-label-caps text-primary border-b border-primary/20 pb-1 hover:border-secondary transition-all"
              href="produtos.php"
              >Ver Tudo</a
            >
          </div>
          <div class="grid grid-cols-1 md:grid-cols-12 gap-8 h-[900px]">
            <!-- Large Feature: Premium Shirts -->
            <div class="md:col-span-8 relative group overflow-hidden bg-white">
              <img
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                data-alt="A close-up shot focusing on the exquisite texture of a premium white cotton shirt. The lighting highlights the fine weave of the fabric and the precision of the stitching on the collar. The background is a soft, warm greige, maintaining a minimalist and editorial luxury aesthetic for a high-end fashion brand."
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgGlyonpJrYzTFQa0Rm1-RFIuzFoKGbQuUVD30qDXMROFByU2YUscP6j_vCaYjMrEvgSl6KY8SHC0Xs2Z9H12K4iLc48qrkceoQ_0Cm3_u-WAkZAPqOPfvKMq95A5ix4FFD7TbzRZwulHPgwD3v5WTawxB2nj3u2GuqlUaVV2t61GtOLpjq_w9f0krw4B-Dse2MbvrsxRCanTh5SkMutDWW5YXjNTr-htBETSiyaYyPGI272c_FTtLyV8TR1ua1AbG73xBkhlz6AIE"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent"
              ></div>
              <div class="absolute bottom-12 left-12">
                <h3 class="font-headline-md text-headline-md text-surface mb-2">
                  Premium Shirts
                </h3>
                <p
                  class="font-label-caps text-label-caps text-surface/80 uppercase"
                >
                  O Toque do Algodão Egípcio
                </p>
              </div>
            </div>
            <!-- Side Stack -->
            <div class="md:col-span-4 flex flex-col gap-8">
              <!-- Polos -->
              <div class="flex-1 relative group overflow-hidden bg-white">
                <img
                  class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                  data-alt="A sophisticated knit polo shirt in deep forest green displayed in a minimalist setting. The shirt is perfectly folded on a stone plinth, emphasizing the soft texture of the yarn and the refined ribbed edges. High-key lighting creates a bright and airy atmosphere typical of a luxury boutique."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuA91R-bK7F0T1YSkH7vLTZPV9b8T2WG7AyXVzmuxjb4hPPTMviFCIYEnUKzWP-hpSXUEBP-P-DF--pyZVxnnmSF5VV9H85m3giPEhoVxz4MeRuBrIuwD6JQNUUznHeDylIncalmBK8Ssf-inH4eobvDGQF3_s64glROVr8vuYYoySovG7PjTmX1XypEIobqnFJYUhB5GOdeoKWGIGfHyuZwTu0pyfYDnW30ZTLfGUq6t8vEFdOBOqPqPQ4mblBIpVyqEPB4BNKaqdxn"
                />
                <div
                  class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent"
                ></div>
                <div class="absolute bottom-8 left-8">
                  <h3 class="font-headline-md text-[24px] text-surface mb-1">
                    Polos
                  </h3>
                  <p
                    class="font-label-caps text-[10px] text-surface/80 uppercase"
                  >
                    Casual Sophistication
                  </p>
                </div>
              </div>
              <!-- Casual Tailoring -->
              <div class="flex-1 relative group overflow-hidden bg-white">
                <img
                  class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                  data-alt="A man's silhouette wearing a tailored casual blazer in a charcoal tone. He is adjusting his cufflink, showcasing the impeccable fit and movement of the garment. The lighting is moody and dramatic, focusing on the silhouette and the quality of the wool fabric against a dark, textured wall."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuD9KCQxw-_mHQIXFMGmErx7jgErKvRqCCIHdJlDY2Ug0OM5qxb1tCC4-j57IaM5VTyrEbjmyLCfNohgGsfA181XBFyY427SFGUD2ZgMe7j03Gg_TSX5hwPhnOwadTPoCzGo3OdIh3va4cZxi-QDvdjz6atTcoIoE22HXPd8_31gEmf5AVmZ2X6gcOXdx_MH5obc4nICrfPaQXC0nrwtFSh0B3O0z0wuUKTOjlJohQs4m4axbVetk2R4FcCQ_ap5vb_XvejllfPk-ubT"
                />
                <div
                  class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent"
                ></div>
                <div class="absolute bottom-8 left-8">
                  <h3 class="font-headline-md text-[24px] text-surface mb-1">
                    Casual Tailoring
                  </h3>
                  <p
                    class="font-label-caps text-[10px] text-surface/80 uppercase"
                  >
                    Estrutura e Liberdade
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Section 3: O Lobo -->
      <section
        class="py-section-gap px-16 bg-primary text-surface overflow-hidden"
      >
        <div
          class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-24 items-center"
        >
          <div class="relative">
            <div
              class="absolute -top-12 -left-12 w-64 h-64 border border-surface/10"
            ></div>
            <img
              class="relative z-10 w-full max-w-md mx-auto opacity-90"
              data-alt="The Lupière brand symbol, a minimalist and powerful stylized wolf head icon. The design is geometric and clean, representing heritage, strength, and silent authority. It is rendered in a high-contrast white against a deep forest green background, embodying the luxury tailoring brand's essence."
              src="public/assets/img/logo.jpg"
            />
            <div
              class="absolute -bottom-12 -right-12 w-64 h-64 border border-surface/10"
            ></div>
          </div>
          <div>
            <span
              class="font-label-caps text-label-caps text-secondary-fixed-dim block mb-8"
              >HERANÇA</span
            >
            <h2 class="font-headline-display text-headline-lg mb-10">O Lobo</h2>
            <div class="space-y-6 font-body-lg text-surface/80 max-w-lg">
              <p>
                O lobo não é apenas nosso símbolo; é nosso guia. Representa o
                indivíduo que valoriza seu bando, mas que possui a força e a
                astúcia para caminhar sozinho.
              </p>
              <p>
                Na Lupière, o lobo simboliza a vigilância sobre a qualidade e a
                lealdade ao ofício da alfaiataria. É a marca de quem não segue
                tendências, mas deixa sua própria trilha.
              </p>
            </div>
            <div class="mt-16 flex items-center gap-6">
              <div class="h-[1px] w-12 bg-secondary-fixed-dim"></div>
              <span class="font-label-caps text-[10px] tracking-[0.3em]"
                >EST. 2025 — ALTA ALFAIATARIA</span
              >
            </div>
          </div>
        </div>
      </section>
      <!-- Footer -->
      <footer
        class="bg-[#FAF9F4] w-full py-24 px-8 md:px-16 border-t border-[#1B3022]/10"
      >
        <div
          class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12"
        >
          <div class="col-span-1 md:col-span-1">
            <div
              class="text-2xl font-headline-lg tracking-[0.3em] text-[#1B3022] uppercase mb-8"
            >
              LUPIÈRE
            </div>
            <p class="font-body-md text-[#1B3022]/60 max-w-xs">
              Elevando a alfaiataria clássica para o homem contemporâneo. Rigor,
              tradição e personalidade.
            </p>
          </div>
          <div class="flex flex-col gap-4">
            <h4
              class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
            >
              Explorar
            </h4>
            <a
              class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
              href="#"
              >Inicio</a
            >
            <a
              class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
              href="#"
              >Coleções</a
            >
            <a
              class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
              href="#"
              >Acessórios</a
            >
          </div>
          <div class="flex flex-col gap-4">
            <h4
              class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
            >
              Atendimento ao Cliente
            </h4>
            <a
              class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
              href="#"
              >Envio &amp; Devoluções</a
            >
            <a
              class="font-body-md text-[#1B3022]/60 hover:text-[#1B3022] transition-colors"
              href="#"
              >Política de Privacidade</a
            >
          </div>
          <div class="flex flex-col gap-4">
            <h4
              class="font-label-caps text-[12px] text-[#1B3022] uppercase tracking-widest mb-4"
            >
              Atelier
            </h4>
            <p class="font-body-md text-[#1B3022]/60">
              Avenida da Liberdade, 110<br />
              1250-146 Lisboa, Portugal
            </p>
            <div class="mt-4 flex gap-6">
              <a
                class="text-[#1B3022]/60 hover:text-[#1B3022]"
                href="https://www.instagram.com/uselupiere/"
                target="_blank"
              >
                <span class="material-symbols-outlined text-[20px]">
                  photo_camera
                </span>
              </a>
              <a class="text-[#1B3022]/60 hover:text-[#1B3022]" href="mailto:info@lupiere.com" target="_blank">
                <span class="material-symbols-outlined text-[20px]"
                  >mail</span
                ></a
              >
            </div>
          </div>
        </div>
        <div
          class="max-w-[1440px] mx-auto mt-24 pt-12 border-t border-[#1B3022]/5 flex flex-col md:flex-row justify-between items-center gap-6"
        >
          <div
            class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase"
          >
            © 2026 LUPIÈRE TAILORS. ALL RIGHTS RESERVED.
          </div>
          <div class="flex gap-8">
            <a
              class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
              href="#"
              >Termos</a
            >
            <a
              class="font-label-caps text-[10px] tracking-[0.2em] text-[#1B3022]/40 uppercase hover:text-[#1B3022] transition-colors"
              href="#"
              >Cookies</a
            >
          </div>
        </div>
      </footer>
    </main>

    <script src="public/assets/js/main.js"></script>
  </body>
</html>
