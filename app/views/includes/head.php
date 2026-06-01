<!doctype html>
<html class="light" lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo isset($titulo_pagina) ? $titulo_pagina . ' | LUPIÈRE' : 'LUPIÈRE'; ?></title>
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
      :focus-visible {
        outline: 3px solid rgba(115, 92, 0, 0.45);
        outline-offset: 3px;
      }
      html {
        scroll-behavior: smooth;
      }
      body {
        overflow-x: hidden;
      }
      img,
      video,
      canvas,
      svg {
        max-width: 100%;
      }
      button,
      a,
      input,
      select,
      textarea {
        touch-action: manipulation;
      }
      input,
      select,
      textarea,
      button {
        font-size: 16px;
      }
      .admin-menu-toggle {
        position: fixed;
        left: 16px;
        top: 14px;
        z-index: 95;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(27, 48, 34, 0.15);
        background: #faf9f4;
        color: #061b0e;
        box-shadow: 0 12px 32px rgba(6, 27, 14, 0.14);
        border-radius: 8px;
      }
      .admin-sidebar-overlay {
        position: fixed;
        inset: 0;
        z-index: 70;
        background: rgba(0, 0, 0, 0.35);
        opacity: 0;
        pointer-events: none;
        transition: opacity 180ms ease;
      }
      body.admin-sidebar-open .admin-sidebar-overlay {
        opacity: 1;
        pointer-events: auto;
      }
      @media (min-width: 1025px) {
        .admin-menu-toggle {
          left: 20px;
        }
        body.admin-sidebar-collapsed aside.fixed.top-0.left-0.h-full {
          width: 76px !important;
        }
        body.admin-sidebar-collapsed aside.fixed.top-0.left-0.h-full > div:first-child {
          opacity: 0;
          height: 64px;
          overflow: hidden;
          pointer-events: none;
        }
        body.admin-sidebar-collapsed aside.fixed.top-0.left-0.h-full nav a {
          justify-content: center;
          padding-left: 0.75rem;
          padding-right: 0.75rem;
        }
        body.admin-sidebar-collapsed aside.fixed.top-0.left-0.h-full nav a span.ml-3 {
          display: none;
        }
        body.admin-sidebar-collapsed main.ml-64 {
          margin-left: 76px !important;
        }
        body.admin-sidebar-collapsed header.left-64 {
          left: 76px !important;
        }
      }
      @media (max-width: 1024px) {
        .admin-menu-toggle {
          display: inline-flex;
        }
        aside.fixed.top-0.left-0.h-full.w-64 {
          width: min(82vw, 320px) !important;
          transform: translateX(-105%);
          transition: transform 220ms ease;
          z-index: 90 !important;
          overflow-y: auto;
          -webkit-overflow-scrolling: touch;
        }
        body.admin-sidebar-open aside.fixed.top-0.left-0.h-full.w-64 {
          transform: translateX(0);
        }
        main.ml-64 {
          margin-left: 0 !important;
          width: 100%;
        }
        header.left-64 {
          left: 0 !important;
          padding-left: 64px;
        }
        header.left-64 .tracking-\[0\.4em\],
        header.left-64 .tracking-\[0\.3em\] {
          letter-spacing: 0.12em !important;
        }
        .py-section-gap {
          padding-top: 88px !important;
          padding-bottom: 48px !important;
        }
        .px-gutter {
          padding-left: 16px !important;
          padding-right: 16px !important;
        }
        table {
          min-width: 680px;
        }
        .overflow-x-auto {
          -webkit-overflow-scrolling: touch;
        }
      }
      @media (max-width: 640px) {
        .font-headline-display,
        .text-\[72px\],
        .text-\[64px\],
        .text-\[48px\] {
          font-size: 36px !important;
          line-height: 1.12 !important;
        }
        .text-headline-lg,
        .font-headline-lg {
          font-size: 30px !important;
          line-height: 1.2 !important;
        }
        .text-headline-md,
        .font-headline-md {
          font-size: 24px !important;
          line-height: 1.25 !important;
        }
        .grid {
          min-width: 0;
        }
      }
    </style>
  </head>
  <body
    class="bg-background text-on-surface font-body-md min-h-screen flex flex-col"
  >
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('aside.fixed.top-0.left-0.h-full');
        const main = document.querySelector('main.ml-64');
        if (!sidebar || !main) return;

        sidebar.setAttribute('aria-label', 'Menu administrativo');

        const overlay = document.createElement('div');
        overlay.className = 'admin-sidebar-overlay';
        overlay.setAttribute('aria-hidden', 'true');
        document.body.appendChild(overlay);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'admin-menu-toggle';
        toggle.setAttribute('aria-label', 'Abrir menu administrativo');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.innerHTML = '<span class="notranslate material-symbols-outlined" translate="no">menu</span>';
        document.body.appendChild(toggle);

        function isMobileAdmin() {
          return window.matchMedia('(max-width: 1024px)').matches;
        }

        function closeSidebar() {
          document.body.classList.remove('admin-sidebar-open');
          toggle.setAttribute('aria-expanded', 'false');
          toggle.setAttribute('aria-label', 'Abrir menu administrativo');
        }

        function openSidebar() {
          document.body.classList.add('admin-sidebar-open');
          toggle.setAttribute('aria-expanded', 'true');
          toggle.setAttribute('aria-label', 'Fechar menu administrativo');
        }

        toggle.addEventListener('click', function () {
          if (isMobileAdmin()) {
            if (document.body.classList.contains('admin-sidebar-open')) {
              closeSidebar();
            } else {
              openSidebar();
            }
            return;
          }

          document.body.classList.toggle('admin-sidebar-collapsed');
          const collapsed = document.body.classList.contains('admin-sidebar-collapsed');
          toggle.setAttribute('aria-expanded', String(!collapsed));
          toggle.setAttribute('aria-label', collapsed ? 'Expandir menu administrativo' : 'Recolher menu administrativo');
        });

        overlay.addEventListener('click', closeSidebar);

        sidebar.querySelectorAll('a').forEach(function (link) {
          link.addEventListener('click', function () {
            if (isMobileAdmin()) closeSidebar();
          });
        });

        document.addEventListener('keydown', function (event) {
          if (event.key === 'Escape') closeSidebar();
        });

        window.addEventListener('resize', function () {
          if (!isMobileAdmin()) closeSidebar();
        });
      });
    </script>
