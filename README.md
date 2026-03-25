Plataforma Web para Conexão entre Contratantes e Freelancers

O MeuFreela é um ecossistema desenvolvido para profissionalizar a busca por serviços autônomos na região de Jaraguá do Sul. Esta revisão (V2) foca na robustez do backend, segurança da informação e escalabilidade da arquitetura.

🏗️ Arquitetura e Organização
O projeto adota uma separação clara de responsabilidades para facilitar a manutenção e evolução do sistema:

Frontend: Camada de apresentação e interface do usuário (renderização, CSS e páginas dinâmicas).

Backend: Motor de regras de negócio, autenticação segura, persistência de dados e processamento de formulários.

Infraestrutura: Configurações de banco de dados e schemas SQL otimizados com chaves estrangeiras (FKs) e índices.

🛡️ Evolução Técnica e Segurança (V2)
Nesta versão, implementei melhorias críticas baseadas em boas práticas de engenharia de software:

Segurança de Sessão: Implementação de session_regenerate_id(true) para prevenir ataques de fixação de sessão.

Integridade de Dados: Padronização de consultas SQL (correção de id_vaga vs vaga_id) e uso de parameter binding para prevenir SQL Injection.

Fluxos de Usuário: Implementação completa do sistema de recuperação de senha e gestão de dashboard.

Otimização de Banco: Refatoração do schema com foco em performance (índices em candidaturas).

🛠️ Tecnologias Utilizadas
Linguagem: PHP (Backend e Processamento).

Banco de Dados: MySQL/MariaDB (Schema Relacional).

Interface: HTML5, CSS3 e PHP dinâmico.

📅 Próximos Passos (Roadmap)
[ ] Implementação de Tokens CSRF para proteção de formulários.

[ ] Configuração de variáveis de ambiente (APP_ENV) para gestão de erros.

[ ] Sistema de Rate Limiting para segurança em tentativas de login.

[ ] Módulo de upload de arquivos com validação de MIME-type.
