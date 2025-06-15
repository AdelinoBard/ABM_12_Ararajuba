## _setup.php_

O arquivo `_setup.php_` é responsável por configurar as tabelas MySQL que o aplicativo utilizará. Ele **deve ser carregado no navegador antes de executar qualquer outro arquivo**, caso contrário, erros de conexão com o banco de dados poderão ocorrer.

### Estrutura das Tabelas

As tabelas criadas são concisas e organizadas, contendo as seguintes colunas:

- **`members`**: `user` (indexado, nome de usuário), `pass` (armazenamento do hash da senha)
- **`messages`**: `id` (indexado, identificador único), `auth` (indexado, autor), `recip` (destinatário), `pm` (tipo de mensagem), `message` (conteúdo da mensagem)
- **`friends`**: `user` (indexado, nome de usuário), `friend` (nome de usuário do amigo)
- **`profiles`**: `user` (indexado, nome de usuário), `text` (“sobre mim”)

### Segurança e Execução

Antes de criar qualquer tabela, o código **verifica se ela já existe**, permitindo que `_setup.php_` seja executado várias vezes sem causar conflitos ou erros no banco de dados.

**Importante:** Execute `_setup.php_` **antes** de rodar qualquer outro arquivo do projeto, garantindo que todas as tabelas necessárias estejam devidamente criadas.

---

```php
<!DOCTYPE html> <!-- Example 03: setup.php -->
<html>
  <head>
    <title>Configurando o banco de dados</title>
  </head>
  <body>
    <h3>Configurando...</h3>

<?php
  require_once 'functions.php';

  $pdo->query('CREATE TABLE IF NOT EXISTS members (
    user VARCHAR(16),
    pass VARCHAR(255),
    INDEX(user(6))
  )');

  $pdo->query('CREATE TABLE IF NOT EXISTS messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    auth VARCHAR(16),
    recip VARCHAR(16),
    pm CHAR(1),
    time INT UNSIGNED,
    message VARCHAR(4096),
    INDEX(auth(6)),
    INDEX(recip(6))
  )');

  $pdo->query('CREATE TABLE IF NOT EXISTS friends (
    user VARCHAR(16),
    friend VARCHAR(16),
    INDEX(user(6)),
    INDEX(friend(6))
  )');

  $pdo->query('CREATE TABLE IF NOT EXISTS profiles (
    user VARCHAR(16),
    text VARCHAR(4096),
    INDEX(user(6))
  )');
?>

    <br>...feito.
  </body>
</html>
```
