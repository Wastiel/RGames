# RGames — Biblioteca de Jogos

Sistema simples para registrar jogos: plataforma, tipo, tempo jogado, valor original,
valor pago na promoção, se completou, nota e observações.
Segue o mesmo padrão do projeto RA-Atendimento-PHP (PHP puro + PDO + Bootstrap 5).

## Como rodar (XAMPP / Laragon)
1. Copie a pasta `RGames` para `htdocs`.
2. No phpMyAdmin, importe `banco/script.sql`.
3. Confira `includes/conexao.php` (host, usuário e senha).
4. Acesse `http://localhost/RGames/` — login: **admin** / **password**

> Se o login falhar, gere um hash novo e atualize a tabela `usuario`:
> `php -r "echo password_hash('123', PASSWORD_DEFAULT);"`

## Estrutura
- `includes/` conexão, validação de login, header, menu, footer, funções (`h()`, `moeda()`)
- `login/` entrar e sair
- `index/dashboard.php` painel com totais (COUNT, SUM, GROUP BY)
- `jogo/`, `plataforma/`, `tipoJogo/` — cada módulo: `listar`, `form`, `salvar`, `toggleAtivo`
  (`jogo/` ainda tem `completar.php`)

## Exercícios sugeridos
1. Adicionar o campo "horas para completar" e calcular quanto falta.
2. Trocar `toggleAtivo` e `completar` (GET) por formulários POST.
3. Criar o CRUD de usuários.
4. Ordenar a lista clicando no cabeçalho da coluna.
5. Mostrar no dashboard o jogo mais caro e o mais jogado.
