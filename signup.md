## _signup.php_

O arquivo `_signup.php_` permite que novos usuários se cadastrem em nossa rede social. Ele contém um **formulário simples** onde os usuários podem inserir um nome de usuário e uma senha.

### Verificação de Disponibilidade do Nome de Usuário

O formulário conta com uma verificação assíncrona para garantir que o nome de usuário desejado esteja disponível. Para isso, utilizamos um elemento `<span>` vazio com o atributo `id="used"`, que será atualizado dinamicamente.

No final do HTML, há um bloco de **JavaScript** contendo uma função de seta anônima acionada no evento `blur` do campo `username`. Quando o usuário sai desse campo, a função faz uma solicitação ao arquivo `_checkuser.php_`, verificando se o nome está disponível. O resultado da chamada assíncrona, feita via `fetch`, retorna uma mensagem amigável e a insere no `<span id="used">`.

### Processamento do Cadastro

No início do programa, há código **PHP** responsável por validar e registrar o novo usuário no banco de dados:

- A consulta verifica se o nome de usuário já existe.
- Se estiver disponível, insere o novo usuário e sua senha.
- A senha **não** é armazenada em texto simples para evitar riscos de segurança. Em vez disso, utilizamos um **hash unidirecional**.

### Login Após Cadastro

Após um cadastro bem-sucedido, o usuário é direcionado à página de login para acessar sua conta.

**Nota:** Uma opção mais fluida seria autenticar automaticamente o usuário recém-cadastrado, eliminando a etapa manual de login. No entanto, para manter o código simples, os módulos de cadastro e login permanecem separados. Caso deseje implementar essa funcionalidade, basta incluir a lógica necessária.

---

```php
<?php // Exemplo 05: signup.php
  require_once 'header.php';

  // Inicializa as variáveis de erro e usuário
  $erro = $usuario = "";

  // Se houver uma sessão ativa, ela é destruída
  if (isset($_SESSION['user']))
    destroySession();

  // Se o formulário for enviado
  if (isset($_POST['user'])) {
    $usuario = $_POST['user'];

    // Verifica se algum campo está vazio
    if ($_POST['user'] === "" || $_POST['pass'] === "")
      $erro = 'Preencha todos os campos.';
    else {
      // Verifica se o nome de usuário já existe no banco
      $stmt = $pdo->prepare('SELECT * FROM members WHERE user=?');
      $stmt->execute([$usuario]);
      if ($stmt->rowCount())
        $erro = 'Esse nome de usuário já está em uso.<br><br>';
      else {
        // Insere o novo usuário com a senha criptografada
        $stmt = $pdo->prepare('INSERT INTO members VALUES(?, ?)');
        $stmt->execute([
          $usuario,
          password_hash($_POST['pass'], PASSWORD_DEFAULT)
        ]);

        // Finaliza o script e exibe mensagem de sucesso
        die('<h4>Conta criada com sucesso!</h4>Por favor, faça login.</div></body></html>');
      }
    }
  }

  // Sanitiza as variáveis para evitar XSS
  $erro_html = htmlentities($erro);
  $usuario_html = htmlentities($usuario);
?>

<!-- Formulário de cadastro -->
<form method="post" action="signup.php">
  <p class="error">
    <?php echo $erro_html; ?>
  </p>
  <p>Por favor, preencha seus dados para se cadastrar</p>
  <p>
    <label>Usuário</label>
    <input type="text" maxlength="16" name="user" id="username"
      value="<?php echo $usuario_html; ?>"><br>
    <label></label><span id="used">&nbsp;</span>
  </p>
  <p>
    <label>Senha</label>
    <input type="password" name="pass">
  </p>
  <p>
    <label></label>
    <input type="submit" value="Cadastrar">
  </p>
</form>

<script>
  // Verifica se o nome de usuário já está sendo usado (assíncrono)
  const campo = byId('username');
  campo.onblur = () => {
    if (campo.value === '')
      return
    const dados = new FormData()
    dados.set('user', campo.value)
    fetch('checkuser.php', { method: 'post', body: dados })
      .then(response => response.text())
      .then(texto => byId('used').innerHTML = texto)
  }
</script>
```
