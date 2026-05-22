<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

// Verificar se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$usuario = null;

// Função para obter usuário por ID (adicionar em funcoes.php se não existir)
function obter_usuario_por_id($id) {
    $conn = conectar_db();
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        $usuario = null;
    }
    $conn->close();
    return $usuario;
}

// Processar atualização do perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    // Obter usuário atual
    $usuario_atual = obter_usuario_por_id($usuario_id);
    if (!$usuario_atual) {
        $erro = "Usuário não encontrado.";
    } else {
        // Verificar senha atual
        if (!password_verify($senha_atual, $usuario_atual["senha"])) {
            $erro = "Senha atual incorreta.";
        } else {
            // Preparar dados para atualização
            $conn = conectar_db();
            $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', telefone = '$telefone'";

            // Se nova senha foi fornecida e confirmada, atualizar senha
            if (!empty($nova_senha) && $nova_senha == $confirmar_senha) {
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $sql .= ", senha = '$nova_senha_hash'";
            } elseif (!empty($nova_senha) || !empty($confirmar_senha)) {
                $erro = "Nova senha e confirmação devem coincidir.";
            }

            if (!isset($erro)) {
                $sql .= " WHERE id = $usuario_id";
                if ($conn->query($sql) === TRUE) {
                    $sucesso = "Perfil atualizado com sucesso!";
                    // Atualizar sessão se o nome changed
                    $_SESSION["usuario_nome"] = $nome;
                } else {
                    $erro = "Erro ao atualizar perfil: " . $conn->error;
                }
                $conn->close();
            }
        }
    }
}

// Obter dados do usuário para exibição
$usuario = obter_usuario_por_id($usuario_id);
?>
<!doctype html>
<html class="light" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>LUPIÈRE | Perfil</title>
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
    <!-- Sidebar -->
    <aside
      class="fixed top-0 left-0 h-full w-64 bg-primary text-on-primary z-40 flex flex-col"
    >
      <div class="flex items-center justify-center py-8">
        <div
          class="text-xl font-headline-lg tracking-[0.4em] text-white"
        >
          LUPIÈRE
        </div>
      </div>
      <nav class="flex-1 flex-col pt-6 space-y-4">
        <a
          href="../index.php"
          class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
        >
          <span class="material-symbols-outlined">home</span>
          <span class="ml-3">Início</span>
        </a>
        <a
          href="../produtos.php"
          class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors"
        >
          <span class="material-symbols-outlined">inventory_2</span>
          <span class="ml-3">Produtos</span>
        </a>
        <a
          href="perfil.php"
          class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] bg-primary/20 hover:bg-primary/30 transition-colors"
        >
          <span class="material-symbols-outlined">person</span>
          <span class="ml-3">Perfil</span>
        </a>
        <a
          href="../logout.php"
          class="flex items-center px-4 py-3 text-sm font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary/20 transition-colors mt-auto"
        >
          <span class="material-symbols-outlined">logout</span>
          <span class="ml-3">Sair</span>
        </a>
      </nav>
    </aside>
    <!-- Main Content -->
    <main
      class="flex-grow ml-64 flex flex-col"
    >
      <!-- TopNavBar -->
      <header
        class="fixed top-0 left-64 right-0 z-50 bg-[#FAF9F4]/95 backdrop-blur-md border-b border-[#1B3022]/10 h-16 flex items-center"
      >
        <div
          class="flex justify-between items-center w-full px-6 md:px-16 max-w-[1440px] mx-auto"
        >
          <!-- LOGO -->
          <div
            class="text-xl md:text-2xl font-headline-lg tracking-[0.4em] text-[#1B3022]"
          >
            Meu Perfil
          </div>

          <!-- ICONES -->
          <div class="flex items-center gap-5 md:gap-8 text-[#1B3022]">
            <a href="perfil.php" class="icon-btn">
              <span class="material-symbols-outlined">person</span>
            </a>
          </div>
        </div>
      </header>
      <div class="flex-grow flex items-center justify-center py-section-gap px-gutter">
        <div class="max-w-container-max w-full">
          <?php if (isset($sucesso)): ?>
            <div class="mb-6 p-4 bg-green-500/20 text-green-600 rounded-lg">
              <?php echo $sucesso; ?>
            </div>
          <?php endif; ?>
          <?php if (isset($erro)): ?>
            <div class="mb-6 p-4 bg-red-500/20 text-red-600 rounded-lg">
              <?php echo $erro; ?>
            </div>
          <?php endif; ?>

          <div class="space-y-8">
            <!-- Formulário de perfil -->
            <div class="bg-surface rounded-lg border border-outline/20 p-6">
              <h2 class="font-headline-md text-headline-md text-primary mb-4">
                Editar Perfil
              </h2>
              <form action="perfil.php" method="post">
                <div class="space-y-4">
                  <div>
                    <label class="block font-label-caps text-label-caps mb-2">Nome:</label>
                    <input
                      type="text"
                      name="nome"
                      value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>"
                      class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                      required
                    >
                  </div>

                  <div>
                    <label class="block font-label-caps text-label-caps mb-2">Email:</label>
                    <input
                      type="email"
                      name="email"
                      value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>"
                      class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                      required
                    >
                  </div>

                  <div>
                    <label class="block font-label-caps text-label-caps mb-2">Telefone:</label>
                    <input
                      type="tel"
                      name="telefone"
                      value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>"
                      class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                    >
                  </div>

                  <div>
                    <label class="block font-label-caps text-label-caps mb-2">Senha Atual:</label>
                    <input
                      type="password"
                      name="senha_atual"
                      class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                      required
                    >
                  </div>

                  <div>
                    <label class="block font-label-caps text-label-caps mb-2">Nova Senha (deixe em branco para manter a atual):</label>
                    <input
                      type="password"
                      name="nova_senha"
                      class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                    >
                  </div>

                  <div>
                    <label class="block font-label-caps text-label-caps mb-2">Confirmar Nova Senha:</label>
                    <input
                      type="password"
                      name="confirmar_senha"
                      class="w-full form-input-bespoke py-3 text-body-md font-body-md text-primary"
                    >
                  </div>

                  <div class="flex justify-end">
                    <button
                      type="submit"
                      class="bg-primary-container text-white py-3 px-6 font-label-caps text-label-caps tracking-[0.2em] hover:bg-primary transition-all duration-300"
                    >
                      Atualizar Perfil
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
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
  </body>
</html>