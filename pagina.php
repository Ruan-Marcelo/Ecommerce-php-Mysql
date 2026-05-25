<?php
session_start();
require_once __DIR__ . '/app/core/funcoes.php';

$paginas = [
    'termos' => [
        'titulo' => 'Termos de uso',
        'conteudo' => [
            'Ao utilizar a LUPIÈRE, você concorda em fornecer informações verdadeiras no cadastro e usar a plataforma apenas para fins lícitos.',
            'Pedidos, preços, disponibilidade e prazos podem variar conforme estoque, endereço de entrega e confirmação de pagamento.',
            'A LUPIÈRE pode atualizar estes termos para refletir melhorias operacionais, legais ou comerciais.'
        ],
    ],
    'cookies' => [
        'titulo' => 'Política de cookies',
        'conteudo' => [
            'Usamos cookies e recursos equivalentes para manter sessão, carrinho, preferências e segurança da navegação.',
            'Cookies também podem apoiar métricas internas de uso e melhoria da experiência.',
            'Você pode controlar cookies pelo navegador, mas algumas funções podem deixar de operar corretamente.'
        ],
    ],
    'frete-envio' => [
        'titulo' => 'Frete e envio',
        'conteudo' => [
            'O prazo de envio começa após confirmação do pagamento e separação do pedido.',
            'Custos e prazos dependem do endereço informado, modalidade escolhida e disponibilidade logística.',
            'Em caso de inconsistência cadastral ou indisponibilidade, entraremos em contato pelos dados informados na conta.'
        ],
    ],
    'privacidade' => [
        'titulo' => 'Política de privacidade',
        'conteudo' => [
            'Coletamos dados necessários para cadastro, autenticação, compra, atendimento e entrega.',
            'Senhas são armazenadas com hash, e dados de acesso administrativo exigem validação de permissão.',
            'Não vendemos dados pessoais. O compartilhamento ocorre apenas quando necessário para operação, obrigações legais ou proteção contra fraude.'
        ],
    ],
];

$slug = $_GET['p'] ?? 'termos';
$pagina = $paginas[$slug] ?? $paginas['termos'];
$titulo_pagina = $pagina['titulo'];

require_once __DIR__ . '/app/views/includes/header.php';
require_once __DIR__ . '/app/views/includes/navbar.php';
?>

<section class="pt-32 pb-24 px-gutter">
  <div class="max-w-[900px] mx-auto">
    <p class="font-label-caps text-label-caps text-secondary uppercase mb-4">LUPI&Egrave;RE</p>
    <h1 class="font-headline-lg text-headline-lg text-primary mb-10"><?php echo escapar($pagina['titulo']); ?></h1>
    <div class="space-y-6 text-on-surface-variant font-body-lg text-body-lg">
      <?php foreach ($pagina['conteudo'] as $paragrafo): ?>
        <p><?php echo escapar($paragrafo); ?></p>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/app/views/includes/footer.php'; ?>
