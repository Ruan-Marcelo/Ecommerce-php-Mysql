<!doctype html>
<html class="light" lang="en">
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