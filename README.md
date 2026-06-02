# LUPIERE

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

Sistema de e-commerce em PHP para a marca **LUPIERE**, com vitrine de produtos, carrinho, checkout, painel administrativo, pedidos, banners, lista de desejos, avaliacoes, comentarios e modulo completo de campanhas de e-mail.

Projeto desenvolvido por **Ruan Marcelo**.

## Sumario

- [Recursos](#recursos)
- [Arquitetura](#arquitetura)
- [Estrutura](#estrutura)
- [Requisitos](#requisitos)
- [Instalacao](#instalacao)
- [Painel Administrativo](#painel-administrativo)
- [Servicos de E-mail](#servicos-de-e-mail)
- [Automacoes](#automacoes)
- [Seguranca](#seguranca)
- [Testes e Validacao](#testes-e-validacao)
- [Licenca](#licenca)
- [Creditos](#creditos)

## Recursos

- Loja virtual com catalogo, categorias, busca e filtros.
- Pagina de produto com comentarios, avaliacoes e lista de desejos.
- Carrinho de compras em sessao com controle de estoque.
- Checkout com simulacao de Pix, cartao e boleto.
- Integracao preparada para Mercado Pago via webhook.
- Historico de pedidos e perfil do cliente.
- Painel administrativo para produtos, categorias, pedidos, banners e administradores.
- Atualizacao de status de pedido com notificacao por e-mail.
- Newsletter com opt-in no cadastro de usuario.
- Campanhas de e-mail segmentadas por publico.
- Automacoes para promocoes, carrinho abandonado e lista de desejos.
- Configuracao SMTP pelo painel admin.

## Arquitetura

O projeto usa PHP procedural organizado por camadas simples:

- `app/core`: configuracao, funcoes de dominio, banco, seguranca e servicos.
- `app/views/includes`: componentes reutilizaveis de layout.
- `admin`: telas administrativas protegidas por sessao e permissao.
- `public`: assets estaticos, uploads e arquivos publicos.
- `database`: schema SQL e script de preparacao do ambiente.
- `tests`: scripts de validacao funcional.

## Estrutura

```text
lupiere/
|-- admin/
|   |-- index.php
|   |-- produtos.php
|   |-- categorias.php
|   |-- pedidos.php
|   |-- banners.php
|   |-- emails.php
|   `-- administradores.php
|-- app/
|   |-- core/
|   |   |-- config.php
|   |   `-- funcoes.php
|   `-- views/
|       `-- includes/
|-- database/
|   |-- database.sql
|   `-- setup_admin.php
|-- public/
|   |-- assets/
|   `-- uploads/
|-- tests/
|-- index.php
|-- produtos.php
|-- produto.php
|-- carrinho.php
|-- checkout.php
|-- finalizar.php
|-- email_cron.php
|-- webhook_mercadopago.php
|-- README.md
`-- LICENSE
```

## Requisitos

- PHP 8.x
- MySQL ou MariaDB
- Servidor Apache, XAMPP ou ambiente equivalente
- Extensoes PHP recomendadas:
  - `pdo_mysql`
  - `curl`
  - `openssl`
- SMTP valido para envio real de e-mails

## Instalacao

1. Coloque o projeto em:

```text
C:\xampp\htdocs\lupiere
```

2. Crie o banco de dados:

```sql
CREATE DATABASE lupiere CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Importe o schema:

```text
database/database.sql
```

4. Configure o banco em:

```text
app/core/config.php
```

5. Execute a preparacao do ambiente:

```powershell
php database\setup_admin.php
```

6. Acesse:

```text
http://localhost/lupiere
```

## Painel Administrativo

Acesso padrao criado pelo setup:

```text
URL: http://localhost/lupiere/admin
E-mail: admin@lupiere.com
Senha: Admin@123
```

Por seguranca, altere a senha do administrador antes de publicar o projeto.

## Servicos de E-mail

O modulo de e-mail fica em:

```text
http://localhost/lupiere/admin/emails.php
```

Funcionalidades disponiveis:

- Configuracao SMTP pelo painel.
- Envio de e-mail de teste.
- Cadastro manual de inscritos.
- Campanhas com assunto, tipo e conteudo HTML.
- Publicos segmentados:
  - inscritos na newsletter
  - todos os clientes
  - clientes com pedidos
  - clientes com lista de desejos
  - selecao manual de usuarios
- Fila de envio com status e erros.
- Historico de campanhas.

Exemplo de configuracao Gmail:

```text
Host: smtp.gmail.com
Porta: 587
Seguranca: TLS
Usuario: seu-email@gmail.com
Senha: senha de app do Google
Remetente: seu-email@gmail.com
```

## Automacoes

O arquivo abaixo processa automacoes e fila de envio:

```text
email_cron.php
```

Execucao manual:

```powershell
php email_cron.php
```

Para automacao real em Windows, crie uma tarefa no Agendador de Tarefas executando periodicamente:

```powershell
php C:\xampp\htdocs\lupiere\email_cron.php
```

Tambem e possivel proteger o cron com token usando a variavel de ambiente:

```text
LUPIERE_CRON_TOKEN
```

Depois, chame:

```text
http://localhost/lupiere/email_cron.php?token=SEU_TOKEN
```

## Seguranca

Medidas implementadas:

- Acesso ao banco via PDO.
- Prepared statements para consultas SQL.
- Hash de senhas com `password_hash`.
- Verificacao de login e permissao de administrador.
- Sanitizacao de saida com `htmlspecialchars`.
- Validacao de uploads por tipo e tamanho.
- CSRF em fluxos sensiveis de carrinho.
- Rate limit basico em acoes de alta repeticao.
- Redirecionamento seguro para evitar open redirect.
- Controle de opt-in para newsletter e automacoes promocionais.
- Separacao de arquivos publicos, core e views.

Recomendacoes antes de publicar:

- Trocar a senha padrao do admin.
- Usar HTTPS.
- Configurar SMTP com senha de app ou credencial dedicada.
- Definir permissao restrita para `public/uploads`.
- Manter `app/core/config.php` fora de repositorios publicos quando houver credenciais reais.
- Configurar backups periodicos do banco.

## Login social

Defina a URL publica da aplicacao e as credenciais OAuth antes de usar os botoes de Google e Apple:

```env
APP_URL=https://seu-dominio.com
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
APPLE_CLIENT_ID=
APPLE_CLIENT_SECRET=
```

Cadastre `https://seu-dominio.com/oauth_callback.php` como URI de retorno nos dois provedores. No Google, use um cliente OAuth para aplicacao web. Na Apple, `APPLE_CLIENT_ID` corresponde ao Services ID e `APPLE_CLIENT_SECRET` ao client secret JWT assinado gerado para o Services ID.

O callback valida `state`, `nonce`, assinatura OIDC, emissor, audiencia e expiracao antes de criar ou vincular a conta local. A tabela `oauth_identidades` tambem e criada automaticamente na primeira autenticacao para facilitar a atualizacao de instalacoes existentes.
- Revisar logs de erro e envios falhos.

## Testes e Validacao

Validar sintaxe PHP:

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

Executar fluxo completo basico:

```powershell
php tests\test_complete_flow.php
```

Validar preparacao do banco:

```powershell
php database\setup_admin.php
```

Validar processamento de e-mails:

```powershell
php email_cron.php
```

## Licenca

Este projeto esta licenciado sob a **MIT License**. Consulte o arquivo [LICENSE](LICENSE).

## Creditos

- Desenvolvimento e direcao do projeto: **Ruan Marcelo**
- Assistencia tecnica, revisoes e apoio na implementacao: **OpenAI Codex**
- Stack principal: PHP, MySQL, Tailwind CSS e Apache/XAMPP

---

**LUPIERE** - alfaiataria, presenca e tecnologia aplicada ao comercio digital.
