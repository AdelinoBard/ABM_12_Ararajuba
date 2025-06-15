## _checkuser.php_

O arquivo `_checkuser.php_` complementa `_signup.php_`, verificando no banco de dados se um nome de usuário já está em uso e retornando uma resposta adequada.

### Funcionamento

- Como a verificação depende da variável `$pdo` para executar **instruções preparadas**, o arquivo começa incluindo `_functions.php_`.
- Se a variável `$_POST['user']` contiver um valor, o sistema consulta o banco de dados para verificar se o nome de usuário já está cadastrado.
- Com base no resultado, o programa retorna uma das seguintes mensagens:
  - **Nome indisponível:** `"Desculpe, o nome de usuário 'user' já está em uso."`
  - **Nome disponível:** `"O nome de usuário 'user' está disponível."`

A verificação é feita analisando o retorno de `$stmt->rowCount()`, que indica:

- `0` → O nome de usuário **não** foi encontrado, ou seja, está disponível.
- `1` → O nome de usuário **já existe**, sendo necessário escolher outro.

### Personalização da Resposta

Para melhorar a experiência do usuário, utilizamos as entidades HTML `&#x2718;` e `&#x2714;`:

- ❌ (`&#x2718;`) precede a mensagem de nome **indisponível**, estilizada em vermelho (`taken`).
- ✅ (`&#x2714;`) precede a mensagem de nome **disponível**, estilizada em verde (`available`).

**Obs.:** As classes `taken` e `available` são definidas em `_styles.css_`.

---

```php
<?php
// Example 06: checkuser.php

require_once 'functions.php'; // Inclui funções auxiliares, como a conexão com o banco de dados via $pdo

// Verifica se o campo 'user' foi enviado via POST
if (isset($_POST['user'])) {

  // Sanitize do nome de usuário para prevenir XSS ao exibir na tela
  $user_input = trim($_POST['user']); // Remove espaços extras
  $user_html_entities = htmlentities($user_input); // Codifica caracteres especiais HTML

  // Prepara consulta SQL usando prepared statements (protege contra SQL Injection)
  $stmt = $pdo->prepare('SELECT * FROM members WHERE user = ?');

  // Executa a query com o valor do nome de usuário
  $stmt->execute([$user_input]);

  // Verifica se encontrou algum registro com o nome de usuário fornecido
  if ($stmt->rowCount()) {
    // Nome de usuário já existe no banco de dados
    echo "<span class='taken'>&nbsp;&#x2718; O nome de usuário '$user_html_entities' já está em uso.</span>";
  } else {
    // Nome de usuário está disponível
    echo "<span class='available'>&nbsp;&#x2714; O nome de usuário '$user_html_entities' está disponível.</span>";
  }
}
?>
```
