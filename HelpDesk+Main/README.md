# HelpDesk+

HelpDesk+ é um sistema completo de gestão de chamados para suporte técnico, desenvolvido em PHP com padrão MVC, MySQL e frontend moderno.

## Estrutura do Projeto

```
SIstema-de-gest-o-helpDesk-main/
│
├── database/                # Scripts SQL para criação e popular o banco
│   ├── gestao_helpdesk.sql
│   ├── queryCriaDB.sql
│   └── suporte_atendimento.sql
│
└── projetoUmc/
    ├── conexao.php          # Conexão com o banco de dados
    ├── index.php            # Página inicial
    ├── assets/              # Arquivos estáticos (CSS, JS, imagens)
    │   ├── css/
    │   ├── js/
    │   └── img/
    ├── controllers/         # Controladores (lógica de negócio)
    ├── models/              # Modelos (representação das tabelas)
    ├── views/               # Views (páginas HTML/PHP)
    ├── create/              # Telas e scripts de criação de registros
    └── select/              # Telas e scripts de listagem, edição e exclusão
```

## Como rodar o projeto

1. **Banco de Dados:**
   - Importe o script `database/queryCriaDB.sql` (ou outro .sql) no seu MySQL para criar o banco `HelpDeskMais`.
   - O banco já vem com dados de exemplo.

2. **Configuração:**
   - Edite `projetoUmc/conexao.php` se precisar alterar usuário, senha ou host do banco.

3. **Servidor:**
   - Coloque a pasta `projetoUmc` em um servidor local (XAMPP, WAMP, Laragon, etc).
   - Acesse `index.php` pelo navegador.

## Funcionalidades
- Cadastro, edição, listagem e exclusão de:
  - Chamados
  - Clientes
  - Funcionários
  - Cargos
  - Contas do sistema
- Visual moderno e responsivo
- Estrutura MVC para fácil manutenção
- SQL seguro e validado

## Estrutura MVC
- **models/**: Classes PHP que representam as tabelas do banco.
- **controllers/**: Arquivos que processam requisições e controlam o fluxo.
- **views/**: Páginas HTML/PHP exibidas ao usuário.

## Dicas
- Para criar novos módulos, siga o padrão de `model`, `controller` e `view`.
- Scripts de banco de dados ficam em `database/`.
- Arquivos estáticos (CSS, JS, imagens) ficam em `assets/`.