# MeuFreela V2

Plataforma web em PHP para conexão entre contratantes e freelancers.

## Organização por responsabilidade
- Frontend: páginas e interface (renderização)
- Backend: regras de negócio, autenticação, persistência e processamento de formulários

Estrutura atual:
- `frontend/pages` (páginas)
- `frontend/assets/css` (estilos)
- `backend/actions` (processamentos e logout)
- `backend/config` (conexão com banco)
- `backend/database` (schema SQL)

Veja o detalhamento em [docs/ARQUITETURA.md](docs/ARQUITETURA.md).

## Melhorias aplicadas nesta revisão
1. Correção de inconsistência entre `id_vaga` e `vaga_id` em consultas e inserts.
2. Correção do update de perfil em `frontend/pages/dashboard.php` (binding de parâmetros).
3. Endurecimento do login com validação de método/campos e `session_regenerate_id(true)`.
4. Remoção de bloco de debug SQL em produção (`frontend/pages/procurar_vagas.php`).
5. Implementação do fluxo de recuperação de senha (`backend/actions/processa_recuperacao.php`).
6. Melhoria do schema SQL com FKs e índices em `candidatura`.

## Boas práticas recomendadas (próxima etapa)
- CSRF token em todos os formulários POST.
- `APP_ENV` para não exibir mensagens técnicas no frontend.
- Rate limiting no login e recuperação de senha.
- Upload de imagem com whitelist de MIME e tamanho máximo.
- Migrações versionadas para banco (em vez de SQL único manual).
