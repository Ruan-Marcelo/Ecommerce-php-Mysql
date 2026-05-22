<!doctype html>

<html class="light" lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Acessórios | LUPIÈRE</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&amp;family=Manrope:wght@400;600&amp;display=swap"
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
              "secondary-container": "#fed65b",
              "surface-bright": "#faf9f4",
              "surface-container-high": "#e9e8e3",
              outline: "#737973",
              "inverse-on-surface": "#f2f1ec",
              "outline-variant": "#c3c8c1",
              "surface-container-highest": "#e3e3de",
              "surface-tint": "#4d6453",
              "on-primary-fixed": "#0b2013",
              "on-tertiary-container": "#939292",
              tertiary: "#161717",
              "secondary-fixed-dim": "#e9c349",
              "tertiary-fixed": "#e4e2e1",
              secondary: "#735c00",
              surface: "#faf9f4",
              "surface-dim": "#dbdad5",
              "on-tertiary-fixed": "#1b1c1c",
              "on-surface-variant": "#434843",
              "surface-variant": "#e3e3de",
              "surface-container-low": "#f5f4ef",
              primary: "#061b0e",
              "primary-fixed-dim": "#b4cdb8",
              "secondary-fixed": "#ffe088",
              "on-tertiary": "#ffffff",
              "on-secondary-container": "#745c00",
              "tertiary-container": "#2b2b2b",
              "error-container": "#ffdad6",
              "on-tertiary-fixed-variant": "#474747",
              "on-secondary-fixed": "#241a00",
              "on-primary": "#ffffff",
              "surface-container": "#efeee9",
              "on-secondary-fixed-variant": "#574500",
              "primary-fixed": "#d0e9d4",
              "inverse-primary": "#b4cdb8",
              "on-error": "#ffffff",
              "tertiary-fixed-dim": "#c8c6c5",
              "on-primary-fixed-variant": "#364c3c",
              "on-primary-container": "#819986",
              error: "#ba1a1a",
              "on-background": "#1b1c19",
              "surface-container-lowest": "#ffffff",
              "on-secondary": "#ffffff",
              background: "#faf9f4",
              "inverse-surface": "#30312e",
              "on-error-container": "#93000a",
              "primary-container": "#1b3022",
              "on-surface": "#1b1c19",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              unit: "8px",
              "margin-edge": "40px",
              "container-max": "1280px",
              gutter: "24px",
              "section-gap": "120px",
            },
            fontFamily: {
              "headline-md": ["Noto Serif"],
              "body-md": ["Manrope"],
              "label-caps": ["Manrope"],
              "headline-lg": ["Noto Serif"],
              "body-lg": ["Manrope"],
              "headline-display": ["Noto Serif"],
            },
            fontSize: {
              "headline-md": ["32px", { lineHeight: "1.3", fontWeight: "400" }],
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
              "label-caps": [
                "12px",
                {
                  lineHeight: "1.2",
                  letterSpacing: "0.15em",
                  fontWeight: "600",
                },
              ],
              "headline-lg": [
                "40px",
                {
                  lineHeight: "1.2",
                  letterSpacing: "-0.01em",
                  fontWeight: "400",
                },
              ],
              "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
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
      body {
        background-color: #faf9f4;
      }
    </style>
  </head>
  <body class="bg-background text-on-background font-body-md">
    <!-- TopNavBar -->
    <header
      class="bg-[#F9F7F2] dark:bg-[#121212] docked full-width top-0 border-b border-[#1B3022]/10 dark:border-white/10 sticky z-50"
    >
      <!-- BOTÃO MOBILE -->
      <div
        class="flex justify-between items-center w-full px-12 py-8 max-w-[1440px] mx-auto"
      >
        <button
          id="menuBtn"
          class="md:hidden text-[#1B3022] dark:text-[#EAEAEA]"
        >
          <span class="material-symbols-outlined">menu</span>
        </button>
        <div
          class="text-2xl font-serif tracking-[0.4em] text-[#1B3022] dark:text-[#EAEAEA]"
        >
          LUPIÈRE
        </div>
        <nav class="hidden md:flex items-center space-x-12">
          <a
            class="font-serif uppercase tracking-[0.2em] text-xs font-medium text-[#1B3022]/60 dark:text-[#EAEAEA]/60 hover:text-[#C5A059] transition-all duration-500"
            href="index.html"
            >Inicio</a
          >
          <a
            class="font-serif uppercase tracking-[0.2em] text-xs font-medium text-[#1B3022]/60 dark:text-[#EAEAEA]/60 hover:text-[#C5A059] transition-all duration-500"
            href="produtos.html"
            >Coleções</a
          >
          <a
            class="font-serif uppercase tracking-[0.2em] text-xs font-medium text-[#1B3022] dark:text-white border-b border-[#C5A059] pb-1"
            href="acessorios.html"
            >Acessórios</a
          >
          <a
            class="font-serif uppercase tracking-[0.2em] text-xs font-medium text-[#1B3022]/60 dark:text-[#EAEAEA]/60 hover:text-[#C5A059] transition-all duration-500"
            href="sobre.html"
            >Nossa História</a
          >
        </nav>
        <div
          class="flex items-center space-x-6 text-[#1B3022] dark:text-[#EAEAEA]"
        >
          <a
            href="carrinho.html"
            aria-label="Abrir carrinho"
            class="group cursor-pointer transition-all duration-300 hover:text-[#C5A059]"
          >
            <span
              class="material-symbols-outlined transition-transform duration-300 group-hover:scale-110"
            >
              shopping_bag
            </span>
          </a>
          <a
            href="/login.html"
            aria-label="Abrir perfil do usuário"
            class="group cursor-pointer transition-all duration-300 hover:text-[#C5A059]"
          >
            <span
              class="material-symbols-outlined transition-transform duration-300 group-hover:scale-110"
            >
              person
            </span>
          </a>
        </div>
      </div>
    </header>
    <!-- MENU MOBILE -->
    <div
      id="mobileMenu"
      class="fixed top-0 left-[-100%] w-72 h-full bg-[#F9F7F2]/90 dark:bg-[#121212]/90 backdrop-blur-xl z-50 transition-all duration-300 shadow-2xl p-8 flex flex-col gap-8"
    >
      <div class="flex justify-between items-center">
        <span class="font-serif uppercase tracking-widest text-xs">Menu</span>
        <button id="closeMenu">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <a class="mobile-link" href="index.html">Inicio</a>
      <a class="mobile-link" href="produtos.html">Coleções</a>
      <a class="mobile-link text-[#C5A059]" href="acessorios.html"
        >Acessórios</a
      >
      <a class="mobile-link" href="sobre.html">Nossa História</a>
    </div>

    <!-- OVERLAY -->
    <div
      id="overlay"
      class="fixed inset-0 bg-black/30 opacity-0 pointer-events-none transition-all duration-300 z-40"
    ></div>
    <main class="max-w-[1440px] mx-auto px-12 pt-20 pb-32">
      <!-- Page Header -->
      <header
        class="mb-24 flex flex-col md:flex-row md:items-end justify-between border-b border-[#C5A059]/30 pb-12"
      >
        <!-- <div>
          <h1
            class="font-headline-display text-headline-display text-primary mb-4"
          >
            Acessórios
          </h1>
          <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl">
            Descubra nossa coleção de peças essenciais meticulosamente
            elaboradas, onde as técnicas artesanais tradicionais encontram a
            elegância sartorial moderna.
          </p>
        </div> -->
        <div class="mt-8 md:mt-0 flex items-center gap-4">
          <span class="font-label-caps text-label-caps uppercase text-outline"
            >Filtrar por:</span
          >
          <select
            class="bg-transparent border-none border-b border-primary/20 font-label-caps text-label-caps focus:ring-0 focus:border-primary px-0 py-2 cursor-pointer"
          >
            <option>Todos os produtos</option>
            <option>Artigos de luxo</option>
            <option>Gravatas</option>
            <option>Abotoaduras</option>
          </select>
        </div>
      </header>
      <!-- Product Grid -->
      <section
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-24"
      >
        <!-- Item 1: Silk Tie -->
        <article class="flex flex-col group">
          <div class="aspect-[4/5] overflow-hidden mb-8 relative">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="A macro photograph of a luxurious deep emerald green silk tie with a subtle jacquard pattern, draped elegantly over a light grey tailor's mannequin. The lighting is high-contrast, highlighting the exquisite shimmer and texture of the fabric. The background is a soft, minimalist atelier setting with warm, golden-hour light filtering through a window."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuCltp6W5GwhZBM-YP_vJyMJDNAzol3nsIFoB312L1aqePVuykURQZw6XGwYGEgWodAfnmI5X06EJn1Y-xtxf6cb42gRINpeJGWiz59YqRBi90V3IsyCeYSdpK83MtHOeOmwTZ6vshZh-QAhKLyP3C2htke3aspvTK9esqrVFLILLLDha7YuZ1xlgzKL6aogAW0Q1ZZdwusNbs73QiNRDqtUWXvNr03JGjmS3iKt491b0k-Ou-Z89CjaZ5YkjwhJNrVcV1Adr6YarPXN"
            />
            <div
              class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-500"
            ></div>
          </div>
          <div class="flex flex-col flex-grow">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">
              Gravata de Seda Esmeralda
            </h2>
            <p
              class="font-label-caps text-label-caps text-secondary mb-6 tracking-widest"
            >
              R$ 850,00
            </p>
            <a
              class="font-label-caps text-label-caps uppercase border-b border-primary/20 pb-1 self-start hover:border-secondary hover:text-secondary transition-all duration-300"
              href="acessorio-view.html"
              >View Details</a
            >
          </div>
        </article>
        <!-- Item 2: Leather Belt -->
        <article class="flex flex-col group translate-y-12">
          <div class="aspect-[4/5] overflow-hidden mb-8 relative">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="A minimalist composition featuring a handcrafted dark brown full-grain leather belt coiled on a clean off-white surface. The polished brass buckle gleams under soft, diffused studio lighting. The aesthetic is clean and high-end, focusing on the fine stitching and premium leather texture consistent with a luxury fashion house."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfZA3w3iMuJkMb0iqgZFfxhy3U6r_ZO7qH2bFrwSZ9O2KEZjVEFh5sSv2sLesQbA1tqKCnIDUBmtTWFeYGNW9k9UE4PlMET2HsINl_yy_yinOSbQOMG-rJaLtDitp7AVKCwaj59ISMoKt4tdJpcktLlK6tm5aWv_k13OCvvmAAfT7xYZG_EOn1EnAvPEEKEZ3z-jJ23Le5EHotEtk4Kp4Yhqbo_zsxU-LZaDEdgBTZQK4dR-Qcf-TSQPz80buToslEv6Gx_nmLWH3W"
            />
            <div
              class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-500"
            ></div>
          </div>
          <div class="flex flex-col flex-grow">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">
              Cinto em Couro Nobre
            </h2>
            <p
              class="font-label-caps text-label-caps text-secondary mb-6 tracking-widest"
            >
              R$ 1.200,00
            </p>
            <a
              class="font-label-caps text-label-caps uppercase border-b border-primary/20 pb-1 self-start hover:border-secondary hover:text-secondary transition-all duration-300"
              href="acessorio-view.html"
              >View Details</a
            >
          </div>
        </article>
        <!-- Item 3: Cufflinks -->
        <article class="flex flex-col group">
          <div class="aspect-[4/5] overflow-hidden mb-8 relative">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="Close-up of a pair of architectural silver cufflinks with onyx inlay resting on a textured linen shirt cuff. The lighting is dramatic, casting soft shadows that emphasize the sharp geometric lines of the accessory. The mood is sophisticated and masculine, reflecting an editorial luxury fashion aesthetic with neutral tones."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuB3Fv-aufPEmgyqO5xW3nhzKl4tSTXldbUaEKLgMiOmQ4snklqtW5TQisTdkBQRd86bKVny57Hl0d5Z16pklcMuvvLuQvIg3HL4_SzKdv_YV1MBn4C0nsSXrTZl8GzkiPKzHaEI6cADLAJtBGbAZfbz6apBElxMhhRfQRpp1jfMnyN_es5RqhyeXq3JDMT3aO36OFMB7ViZm8wiTYO0n6zVCEOHs1B2dL3UAFthcTkOrS3K5Ci9AyO6-A-XnyITkZrOSs1TADs3LIeQ"
            />
            <div
              class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-500"
            ></div>
          </div>
          <div class="flex flex-col flex-grow">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">
              Abotoaduras Onix Prata
            </h2>
            <p
              class="font-label-caps text-label-caps text-secondary mb-6 tracking-widest"
            >
              R$ 2.450,00
            </p>
            <a
              class="font-label-caps text-label-caps uppercase border-b border-primary/20 pb-1 self-start hover:border-secondary hover:text-secondary transition-all duration-300"
              href="acessorio-view.html"
              >View Details</a
            >
          </div>
        </article>
        <!-- Item 4: Pocket Square -->
        <article class="flex flex-col group">
          <div class="aspect-[4/5] overflow-hidden mb-8 relative">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="A hand-rolled silk pocket square with a classic cream and navy paisley pattern, elegantly puffed in the breast pocket of a charcoal wool suit. The focus is sharp on the delicate silk edges and hand-stitched detailing. The lighting is soft and natural, creating a refined, timeless atmosphere typical of bespoke tailoring."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4DyD3DxYIkf6HE2ITEZA5_1XbSG4nlSrPUmCjSRru-kUO6JkeItameEJ0VkkRNhKDgMxxOCgOrKmTJ8xnK65BZuDFBsyuVC_bC7nRTsE6JVkhzU4mJ1PSo6Nv1VWWZVJ0H4Vn-iZHzjk7z3g7D_BdY83EojFtbMBpVtc8mgEVUv5MBDhI3dj7w27owesZ8K8fMPKe-yAtW_ckNSujxpnDd-M7YImmPKA3st1pRvhChVo-4C5tSPNkNbfextSrzFWZ1tRZwWfBTC1z"
            />
            <div
              class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-500"
            ></div>
          </div>
          <div class="flex flex-col flex-grow">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">
              Lenço de Bolso Paisley
            </h2>
            <p
              class="font-label-caps text-label-caps text-secondary mb-6 tracking-widest"
            >
              R$ 420,00
            </p>
            <a
              class="font-label-caps text-label-caps uppercase border-b border-primary/20 pb-1 self-start hover:border-secondary hover:text-secondary transition-all duration-300"
              href="acessorio-view.html"
              >View Details</a
            >
          </div>
        </article>
        <!-- Item 5: Leather Wallet -->
        <article class="flex flex-col group translate-y-12">
          <div class="aspect-[4/5] overflow-hidden mb-8 relative">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="A slim, elegant bi-fold wallet made from premium black calfskin leather, shown half-open to reveal internal card slots. It is placed on a minimalist dark wood surface with low-angle warm lighting that highlights the pebbled grain of the leather. The composition is clean, sophisticated, and radiates quiet luxury."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuDc6ukIcfSKmWtPbrbgG9DvFrJ9sqxHrJH15_7CGNlIAmOWOQcWv-_8UUD8OyJTrWvtPJ-Bc2eYWWf_RinzYrfFAq8k58bzmPEBUrpGTTt3hlrt7ICjpoX8JtoLGTqylI8Ga9tS600uhUOhoQQuL60CClhdEWjwUOZYgcs13IBQLs3M_vAE6WFDzJLkMlB1MdDHN10DD8qttykGtgejeYBjjjnM7-9qxR4rREItF_IdgVJnTElZrpVtvjq11TxOfdG-WAtVnKlTG4kL"
            />
            <div
              class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-500"
            ></div>
          </div>
          <div class="flex flex-col flex-grow">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">
              Carteira em Couro Calfskin
            </h2>
            <p
              class="font-label-caps text-label-caps text-secondary mb-6 tracking-widest"
            >
              R$ 1.800,00
            </p>
            <a
              class="font-label-caps text-label-caps uppercase border-b border-primary/20 pb-1 self-start hover:border-secondary hover:text-secondary transition-all duration-300"
              href="acessorio-view.html"
              >View Details</a
            >
          </div>
        </article>
        <!-- Item 6: Scarf -->
        <article class="flex flex-col group">
          <div class="aspect-[4/5] overflow-hidden mb-8 relative">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="A luxurious cashmere scarf in a neutral sand color, draped artfully with a soft focus on the fringed edges. The texture is tactile and inviting, captured in high-key, soft white lighting that creates a bright, modern look. The background is a clean, minimalist off-white space, emphasizing the garment's quality and warmth."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4ZMUwJhT9JlofirJmAysMj4xR_zEf0A0cGDqd0ErJGBOrX2-euwjJOz17WCcK0UfVuGCQoNl3Cpl-vnSfMZe8_X_b7XLE8nJCZm41PjYpUv45cM9P48zLl1OWoafdZZhHL0LR4FIoI6zVeXFse3rjqAHgl8RvSS2GtIvknDqdm0w7IFyoRN73B3F-H00RmZR-ngUCsJxKGSMUN_3oMfXPpIcjrG9eMGzSfwWJYEW_TYRqlW1_vm76VH5wgRHEz2NJohvlMQw0xMGp"
            />
            <div
              class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors duration-500"
            ></div>
          </div>
          <div class="flex flex-col flex-grow">
            <h2 class="font-headline-md text-headline-md text-primary mb-2">
              Cachecol em Cashmere
            </h2>
            <p
              class="font-label-caps text-label-caps text-secondary mb-6 tracking-widest"
            >
              R$ 2.900,00
            </p>
            <a
              class="font-label-caps text-label-caps uppercase border-b border-primary/20 pb-1 self-start hover:border-secondary hover:text-secondary transition-all duration-300"
              href="acessorio-view.html"
              >View Details</a
            >
          </div>
        </article>
      </section>
      <!-- Pagination / Load More -->
      <div class="mt-32 flex flex-col items-center">
        <div class="h-px w-24 bg-[#C5A059] mb-8"></div>
        <button
          class="font-label-caps text-label-caps uppercase tracking-[0.3em] text-primary hover:text-secondary transition-colors duration-300 px-8 py-4 border border-primary/10"
        >
          Ver Mais Acessórios
        </button>
      </div>
    </main>
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
            <a
              class="text-[#1B3022]/60 hover:text-[#1B3022]"
              href="mailto:info@lupiere.com"
              target="_blank"
            >
              <span class="material-symbols-outlined text-[20px]">mail</span></a
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

    <script src="main.js"></script>
  </body>
</html>
