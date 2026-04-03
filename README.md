# CoreAtende

![CoreAtende](https://raw.githubusercontent.com/nextincsolutions/omnichannel/main/CoreAtente.png)

Sistema de atendimento via WhatsApp construído em Laravel, integrado à **Evolution API** para consultar conversas, ler mensagens, responder clientes e distribuir atendimentos entre usuários.

## Objetivo

Este projeto existe para facilitar a implementação de uma central de atendimento WhatsApp com:

- visualização de conversas e mensagens;
- envio de mensagens de texto;
- consulta de contatos e status da instância;
- atribuição de chats para responsáveis;
- suporte a mídia (áudio/imagem/vídeo) via proxy de base64;
- filtros operacionais (por responsável, etiqueta e prioridade no frontend).

## Stack

- PHP `^8.3`
- Laravel `^13`
- MySQL (ou outro banco suportado pelo Laravel)
- Node.js + Vite (build frontend)
- Evolution API (obrigatória para funcionamento)

## Requisitos

Antes de iniciar, garanta:

- PHP 8.3+
- Composer
- Node.js 20+ e npm
- Banco de dados configurado
- Instância ativa na Evolution API com `URL`, `API KEY` e `INSTANCE`

## Instalação

1. Clone o repositório.
2. Instale dependências PHP:

```bash
composer install
```

3. Instale dependências frontend:

```bash
npm install
```

4. Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

5. Gere a chave da aplicação:

```bash
php artisan key:generate
```

6. Configure banco de dados no `.env` e rode migrations:

```bash
php artisan migrate
```

7. Inicie o ambiente de desenvolvimento:

```bash
composer run dev
```

Alternativa (processos separados):

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
npm run dev
```

## Configuração da Evolution API

No `.env`, configure:

```env
EVOLUTION_API_URL=http://localhost:8080
EVOLUTION_API_KEY=sua_chave_aqui
EVOLUTION_INSTANCE=sua_instancia_aqui
```

A aplicação lê esses valores em `config/evolutionapi.php`.

## Rotas principais

Prefixo base: `/whatsapp`

- `GET /whatsapp` - Tela principal do atendimento
- `GET /whatsapp/chats` - Lista de conversas
- `POST /whatsapp/messages` - Lista mensagens de um chat
- `POST /whatsapp/send` - Envio de mensagem de texto
- `GET /whatsapp/status` - Estado da instância
- `GET /whatsapp/profile-picture` - Foto de perfil de um número
- `GET /whatsapp/contacts` - Consulta contatos
- `GET /whatsapp/users` - Usuários ativos para atribuição
- `POST /whatsapp/assign` - Atribui/remove responsável do chat
- `GET /whatsapp/assignments` - Lista atribuições
- `POST /whatsapp/media` - Busca mídia em base64

## Fluxo funcional resumido

1. Frontend consulta status da instância.
2. Busca contatos e conversas na Evolution API.
3. Ao abrir um chat, carrega mensagens (com normalização de texto/mídia/status).
4. Ao enviar mensagem, backend repassa para `message/sendText` da Evolution.
5. Operador pode atribuir o chat a um usuário do sistema.

## Boas práticas

### Segurança

- Nunca versione `.env` com segredos reais.
- Use credenciais diferentes por ambiente (dev/staging/prod).
- Faça rotação periódica da `EVOLUTION_API_KEY`.
- Restrinja CORS/IP de acesso da Evolution API sempre que possível.

### Código e manutenção

- Centralize integrações externas em services dedicados (ex.: `EvolutionApiService`) para reduzir lógica no controller.
- Padronize tratamento de erro com mensagens técnicas no log e mensagens neutras na API pública.
- Crie testes de feature para os endpoints críticos (`/send`, `/messages`, `/assign`).
- Evite acoplamento de regra de negócio no Blade/JS inline quando crescer (migrar para módulos JS).

### Operação

- Monitore `storage/logs/laravel.log` para falhas de integração.
- Defina timeout e retry para chamadas à Evolution API.
- Tenha estratégia de reconexão/revalidação da instância quando `status != open`.

## Pontos de atenção atuais

- O endpoint `GET /whatsapp/users` filtra por `active = true`. Garanta que sua tabela `users` possua esse campo (migration adicional), ou ajuste a consulta.
- A view principal usa assets em `public/assets/vendor/...` (Bootstrap e Tabler Icons). Verifique se esses arquivos estão publicados no ambiente.
- Etiquetas e prioridade no frontend são persistidas em `localStorage` do navegador (não no banco).

## Estrutura relevante

- `app/Http/Controllers/Dashboard/WhatsAppController.php` - Orquestra chamadas da Evolution API
- `config/evolutionapi.php` - Configuração da integração
- `routes/web.php` - Rotas HTTP do módulo
- `resources/views/dashboard/whatsapp/index.blade.php` - Interface de atendimento
- `app/Models/WhatsAppChatAssignment.php` - Modelo de atribuição de chats
- `database/migrations/2026_03_25_170239_create_whatsapp_chat_assignments_table.php` - Tabela de atribuições

## Contribuição

Contribuições são bem-vindas.

Para manter qualidade:

1. Crie branch por feature/correção.
2. Faça mudanças pequenas e objetivas.
3. Inclua testes quando alterar comportamento.
4. Descreva impacto técnico no PR (o que muda, riscos, rollback).


## Licença

Este projeto adota o modelo de licença MIT.
