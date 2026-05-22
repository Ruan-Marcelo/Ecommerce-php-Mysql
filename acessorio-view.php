<!doctype html>

<html class="light" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&amp;family=Noto+Serif:ital,wght@0,300;0,400;0,700;1,400&amp;display=swap"
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
              "on-primary-fixed-variant": "#364c3c",
              secondary: "#735c00",
              "on-error": "#ffffff",
              "tertiary-fixed": "#e4e2e1",
              "on-surface": "#1b1c19",
              "primary-container": "#1b3022",
              "on-tertiary-fixed-variant": "#474747",
              "on-primary": "#ffffff",
              "surface-variant": "#e3e3de",
              "primary-fixed-dim": "#b4cdb8",
              "on-error-container": "#93000a",
              "tertiary-fixed-dim": "#c8c6c5",
              "surface-container": "#efeee9",
              outline: "#737973",
              "on-surface-variant": "#434843",
              "surface-bright": "#faf9f4",
              "secondary-fixed": "#ffe088",
              "on-background": "#1b1c19",
              "secondary-container": "#fed65b",
              "on-tertiary-container": "#939292",
              "on-secondary-fixed-variant": "#574500",
              "surface-container-lowest": "#ffffff",
              "surface-tint": "#4d6453",
              "secondary-fixed-dim": "#e9c349",
              "inverse-on-surface": "#f2f1ec",
              "surface-dim": "#dbdad5",
              "primary-fixed": "#d0e9d4",
              background: "#faf9f4",
              "on-primary-container": "#819986",
              "inverse-surface": "#30312e",
              tertiary: "#161717",
              "on-primary-fixed": "#0b2013",
              primary: "#061b0e",
              "surface-container-highest": "#e3e3de",
              "on-tertiary-fixed": "#1b1c1c",
              "tertiary-container": "#2b2b2b",
              "error-container": "#ffdad6",
              "surface-container-high": "#e9e8e3",
              "on-secondary-fixed": "#241a00",
              "inverse-primary": "#b4cdb8",
              "surface-container-low": "#f5f4ef",
              "on-secondary": "#ffffff",
              error: "#ba1a1a",
              "on-secondary-container": "#745c00",
              "on-tertiary": "#ffffff",
              surface: "#faf9f4",
              "outline-variant": "#c3c8c1",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              "margin-edge": "40px",
              unit: "8px",
              "section-gap": "120px",
              gutter: "24px",
              "container-max": "1280px",
            },
            fontFamily: {
              "body-md": ["Manrope"],
              "label-caps": ["Manrope"],
              "headline-md": ["Noto Serif"],
              "headline-lg": ["Noto Serif"],
              "body-lg": ["Manrope"],
              "headline-display": ["Noto Serif"],
            },
            fontSize: {
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
              "label-caps": [
                "12px",
                {
                  lineHeight: "1.2",
                  letterSpacing: "0.15em",
                  fontWeight: "600",
                },
              ],
              "headline-md": ["32px", { lineHeight: "1.3", fontWeight: "400" }],
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
  <body class="font-body-md text-on-background antialiased">
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

    <main class="max-w-[1440px] mx-auto">
      <!-- Product Detail Section -->
      <section class="flex flex-col md:flex-row min-h-[90vh]">
        <!-- Hero Image Block -->
        <div
          class="w-full md:w-3/5 lg:w-2/3 h-[60vh] md:h-auto relative overflow-hidden bg-surface-container"
        >
          <img
            class="w-full h-full object-cover"
            data-alt="A cinematic, extreme close-up of a luxurious Silk Emerald Tie showcasing its intricate weave and deep forest green texture. The lighting is dramatic and moody, emphasizing the subtle sheen of the Italian silk and the hand-stitched rolled edges. The aesthetic is minimal and high-end, using a sophisticated palette of deep greens and soft off-white shadows for a quiet authority feel."
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgQdQ60QGXMzLY2bJE99DDdtvlJeyT3kZapiWNuTF-72b64I455_cqw2EecbvhwPQqyI9LcgYSca8TIrTQ7saUU4mI-SXzW1xQA9KKnjSQUx0EzDnDMTnuhnrBGOtnagbnCSmX-IquzD-1vZQA35ts8qJgXn6Rjr2jf57yqQBrT9M0Ml_yyQemqIgOh-jgDRAXJUz03SQ9Ngk1pdxHh-YCZX6mzLt7OkU7wvT1GpkIGzsTf2xzNmjcQmuw4DvfKdct9m9-gkD8UuN7"
          />
        </div>
        <!-- Details Block -->
        <div
          class="w-full md:w-2/5 lg:w-1/3 px-8 py-16 md:px-12 md:py-24 flex flex-col justify-center bg-background"
        >
          <div class="max-w-md mx-auto md:mx-0">
            <nav class="mb-8">
              <span
                class="text-label-caps font-label-caps text-on-surface-variant uppercase"
                >Accessories / Ties</span
              >
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-primary mb-2">
              Silk Emerald Tie
            </h1>
            <p class="font-headline-md text-headline-md text-secondary mb-8">
              R$ 850,00
            </p>
            <div class="mb-12">
              <p
                class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed"
              >
                Hand-stitched from the finest Italian silk, this tie features a
                subtle sheen and a rich emerald hue, perfect for adding a touch
                of quiet authority to any formal ensemble.
              </p>
            </div>
            <!-- Specifications -->
            <div
              class="space-y-6 mb-12 py-8 border-y border-outline-variant/30"
            >
              <div class="flex justify-between items-center">
                <span
                  class="font-label-caps text-label-caps uppercase text-on-surface-variant"
                  >Material</span
                >
                <span class="font-body-md text-body-md text-primary"
                  >100% Italian Silk</span
                >
              </div>
              <div class="flex justify-between items-center">
                <span
                  class="font-label-caps text-label-caps uppercase text-on-surface-variant"
                  >Width</span
                >
                <span class="font-body-md text-body-md text-primary">8cm</span>
              </div>
              <div class="flex justify-between items-center">
                <span
                  class="font-label-caps text-label-caps uppercase text-on-surface-variant"
                  >Construction</span
                >
                <span class="font-body-md text-body-md text-primary"
                  >7-fold construction, hand-rolled edges</span
                >
              </div>
            </div>
            <!-- Actions -->
            <div class="space-y-4">
              <button
                class="w-full bg-primary-container text-on-primary py-5 font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity"
              >
                Add to Bag
              </button>
              <button
                class="w-full border border-outline text-primary py-5 font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container transition-colors"
              >
                Book a Concierge
              </button>
            </div>
            <div class="mt-12 flex items-center gap-4 text-on-surface-variant">
              <span
                class="material-symbols-outlined text-[20px]"
                data-icon="local_shipping"
                >local_shipping</span
              >
              <span
                class="font-label-caps text-[10px] uppercase tracking-widest"
                >Complimentary Express Shipping on orders over R$ 2.000</span
              >
            </div>
          </div>
        </div>
      </section>
      <!-- Secondary Detail / Textures -->
      <section
        class="py-[120px] px-8 md:px-24 grid grid-cols-1 md:grid-cols-2 gap-24 items-center"
      >
        <div class="order-2 md:order-1">
          <img
            class="w-full aspect-[4/5] object-cover"
            data-alt="A high-contrast macro shot of green silk fabric draped over a tailor's mahogany table. The lighting catches the peaks of the folds, highlighting the exquisite texture and deep forest green color palette. The mood is authentic and sophisticated, emphasizing the artisanal quality of the hand-stitched Italian silk. Pristine white background elements provide a minimalist luxury feel."
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHS6cZXdIIM-xOgcrNUbORL0kkIcd-45owIZgM94-u0XlfBb-LF0KsTpKcYKTE0o0AeRW94Kj4wfYjtX8NqnwTe7w_5N-jWbAyJry4fBQ0KElggDXvC6l48idjLMfN_Q8zqeg4x1uLZ7sfjkcf5GxWEoPMLEsVd4VQVu_r82bn4dBn94c1H1jku1oFAZkHebLQDWVYbVYxglqcPVlK3vPxWHgobhhNCZ2LWkVstDnahoEsA8PJiafb1zdQSNwD2etfYY-BhVGBkWxE"
          />
        </div>
        <div class="order-1 md:order-2 space-y-8">
          <h2 class="font-headline-lg text-headline-lg text-primary">
            The Art of the Emerald
          </h2>
          <div class="w-12 h-[1px] bg-secondary"></div>
          <p class="font-body-lg text-body-lg text-on-surface-variant">
            Our emerald hue is achieved through a multi-stage artisanal dyeing
            process in the heart of Como, Italy. This specific depth of green
            was selected for its versatility—striking against white poplin yet
            subtle enough for charcoal wool.
          </p>
          <div
            class="pt-8 grid grid-cols-2 gap-8 border-t border-outline-variant/30"
          >
            <div>
              <p
                class="font-label-caps text-label-caps uppercase text-secondary mb-2"
              >
                Heritage
              </p>
              <p class="font-body-md text-body-md text-on-surface">
                Milanese Workshop
              </p>
            </div>
            <div>
              <p
                class="font-label-caps text-label-caps uppercase text-secondary mb-2"
              >
                Weight
              </p>
              <p class="font-body-md text-body-md text-on-surface">
                Premium 24-momme silk
              </p>
            </div>
          </div>
        </div>
      </section>
      <!-- Styling Suggestions -->
      <section class="py-[120px] bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-8">
          <div class="text-center mb-16">
            <span
              class="font-label-caps text-label-caps uppercase text-secondary tracking-[0.3em]"
              >Styling Suggestions</span
            >
            <h2 class="font-headline-lg text-headline-lg text-primary mt-4">
              The Art of the Knot
            </h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="space-y-6">
              <div class="aspect-[4/5] bg-surface-variant overflow-hidden">
                <img
                  class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700"
                  data-alt="A fashion-forward portrait showing the Silk Emerald Tie paired with a Lupière white poplin shirt and a charcoal wool blazer. The lighting is soft and editorial, coming from the side to define the knot. The aesthetic is clean and minimalist luxury, highlighting the intersection of traditional tailoring and modern style within a neutral grey and white environment."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDp5zfI-eAtAd0qiApb0h5-zc1LylIFFuYY112sALWSjSd7FZ1TwWYGDjkNZHBiEKs5K98GaZBdLSLlMfhRvMNEpbAc-kftR-yzOpxZgOvnnGDssG3GAhUf9ckWgu8-1HmgX-SSX7s6CYfG4N81KTlrGWYGCySaC0CWj1dQzgieq5O5O0j2zTaS8662EXA1z0ucICTphRXumdx24iqwYGSD3kMPV3DlyOvCihMts16AE-qFhxx_WOUrzzi4aXTzWisFknK2LADJQBZ3"
                />
              </div>
              <div class="px-2">
                <h3 class="font-headline-md text-[24px] text-primary mb-2">
                  The Four-in-Hand
                </h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  A versatile, asymmetrical knot that highlights the tie's
                  hand-rolled edges and natural drape.
                </p>
              </div>
            </div>
            <div class="space-y-6 mt-12 md:mt-24">
              <div class="aspect-[4/5] bg-surface-variant overflow-hidden">
                <img
                  class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700"
                  data-alt="Close-up photograph of hands adjusting the Silk Emerald Tie on a crisp white shirt collar. High-contrast lighting emphasizes the fabric textures of the silk and the poplin cotton. The visual style is editorial and authentic, capturing a candid tailoring moment with sophisticated shadows and a pristine, luxury aesthetic."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuC90s4Km85ttAOGFiHcV0AUAAqTC1G0L3ccXpp0qLkSUYyoddqudIrES_M8JcncHxllVJECM7YXKiPgBZiU70V_VZsXl3bfk2wlV2YRblk_vEarkC1l_dk5zAXxKwnDjjVni636mxi6JCZ8zaL6rCNnB_LXiYsiPm3HgbURVcqyodRdDWqLQoAUHEOFOzKPRUy1xw78o5_6-knKZYG3INMEHCjVHfh4e7ACvcyRoaN1o944Xo1i6MNbbLpb0AfGCKLTwKCpbNzf7V4Z"
                />
              </div>
              <div class="px-2">
                <h3 class="font-headline-md text-[24px] text-primary mb-2">
                  The Half Windsor
                </h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  A classic choice for the wide-spread collar of our bespoke
                  poplin shirts, providing a symmetric, authoritative look.
                </p>
              </div>
            </div>
            <div class="space-y-6">
              <div class="aspect-[4/5] bg-surface-variant overflow-hidden">
                <img
                  class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700"
                  data-alt="A lifestyle product shot featuring the Silk Emerald Tie lying next to a pair of sterling silver cufflinks and a Lupière white shirt. The composition is asymmetrical and elegant, using a soft-focus background and a light-mode color palette of off-white and deep green. The image conveys a sense of high-end preparation and exclusive craftsmanship."
                  src="https://lh3.googleusercontent.com/aida/ADBb0uhCEUISheRANO1PGb3eXb_mYHgBNfSGduPyVIkLKAz1HAgkBoFCBjFW_hZVUF1MXFF-RWy3dNKCEzo39vOD68gAbQOmyS7TmKW0l94i_T5AwdMXGHHQx2hizKKkvpJip_OBxdhMfzBNqQxThYCNp7cPrTpR85YbiVNMqeh_z4JaE-ibfiytr2Ti9Yr6JOla6ZFcWTdzykIoDZ9UB5t6sgxXfdMjxXFtihdAUDpqChkmDliGf_72AmH8FlwPG40a3c8T-7ffLzJcG9w"
                />
              </div>
              <div class="px-2">
                <h3 class="font-headline-md text-[24px] text-primary mb-2">
                  Evening Refinement
                </h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Pair with midnight navy tailoring for a look that commands
                  respect through subtle tonal shifts.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Newsletter / Transition -->
      <section class="py-[120px] px-8 text-center max-w-4xl mx-auto">
        <h3 class="font-headline-lg text-headline-lg text-primary mb-6 italic">
          Adaptado à sua viagem.
        </h3>
        <p class="font-body-lg text-body-lg text-on-surface-variant mb-12">
          Receba acesso exclusivo a novas coleções e alfaiataria sob medida
eventos em nosso ateliê.
        </p>
        <div class="flex flex-col md:flex-row gap-4 justify-center items-end">
          <div class="w-full md:w-80">
            <label
              class="block font-label-caps text-label-caps uppercase text-left mb-2"
              >Endereço de email</label
            >
            <input
              class="w-full bg-transparent border-b border-outline text-primary py-2 px-0 focus:outline-none focus:border-secondary transition-colors"
              placeholder="atelier@lupiere.com"
              type="email"
            />
          </div>
          <button
            class="bg-primary-container text-on-primary px-12 py-3 font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90"
          >
            Join
          </button>
        </div>
      </section>
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
  </body>
</html>
