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