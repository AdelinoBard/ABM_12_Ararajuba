## _login.php_

Com os usuários agora podendo se inscrever no site, `_login.php_` fornece o código necessário para que eles acessem suas contas. Assim como a página de inscrição, ele apresenta um **formulário HTML simples**, além de realizar verificações básicas de erro e utilizar **instruções preparadas e marcadores de posição** para consultas no banco de dados MySQL.

### Autenticação de Usuário

A verificação da senha segue um processo seguro:

1. O sistema consulta o **hash da senha** armazenado no banco de dados, utilizando o nome de usuário como referência.
2. O hash recuperado e a senha informada no login são passados para a função `password_verify()`, que retorna `true` se os valores coincidirem.
3. Esse método evita comparações diretas de strings e garante uma autenticação mais segura.

### Sessão do Usuário

Após uma autenticação bem-sucedida:

- A variável de sessão `$_SESSION['user']` recebe o nome de usuário.
- Durante a sessão ativa, essa variável estará acessível aos demais arquivos do projeto, permitindo a identificação automática do usuário logado.
- **Importante:** O armazenamento da senha ou do hash na sessão **não** é necessário e **representaria um risco à segurança**, pois esses dados poderiam ser acessados de forma não segura.

### Redirecionamento Após Login

Uma vez autenticado, o usuário é **redirecionado automaticamente** para a página inicial da plataforma. Esse redirecionamento ocorre via `header("Location: members.php?view=$user")`, que envia um cabeçalho HTTP para o navegador, carregando `_members.php_` com o nome de usuário como parâmetro.

### Proteção da Senha no Formulário

O campo de entrada da senha no formulário utiliza `type="password"`, garantindo que os caracteres sejam mascarados com asteriscos, protegendo a informação de olhares indesejados.

---

```php
<?php
// Exemplo 07: login.php
// Página de login do sistema

// Inclui o cabeçalho da página
require_once 'header.php';

// Inicializa variáveis para armazenar mensagens de erro e nome de usuário
$error = $user = "";

// Verifica se o formulário foi submetido (campo 'user' existe no POST)
if (isset($_POST['user'])) {
    $user = $_POST['user'];

    // Valida se os campos foram preenchidos
    if ($user === "" || $_POST['pass'] === "") {
        $error = 'Todos os campos devem ser preenchidos';
    } else {
        // Prepara e executa a consulta SQL para buscar o usuário
        $stmt = $pdo->prepare('SELECT user,pass FROM members WHERE user=?');
        $stmt->execute([$user]);
        $result = $stmt->fetchAll();

        // Verifica se o usuário existe e se a senha está correta
        if (count($result) === 0 || !password_verify($_POST['pass'], $result[0]['pass'])) {
            $error = "Tentativa de login inválida";
        } else {
            // Login bem-sucedido: armazena usuário na sessão e redireciona
            $_SESSION['user'] = $user;
            header('Location: members.php?view=' . $user);
            exit(); // Importante para evitar execução adicional do script
        }
    }
}

// Converte caracteres especiais para entidades HTML (prevenção XSS)
$error_html_entities = htmlentities($error);
$user_html_entities = htmlentities($user);
?>

<!-- Formulário de Login -->
<form method="post" action="login.php">
    <!-- Exibe mensagens de erro -->
    <p class="error">
        <?php echo $error_html_entities; ?>
    </p>

    <p>
        Por favor, insira suas credenciais para fazer login
    </p>

    <p>
        <label>Nome de Usuário</label>
        <input type="text" maxlength="16" name="user"
            value="<?php echo $user_html_entities; ?>">
    </p>

    <p>
        <label>Senha</label>
        <input type="password" name="pass">
    </p>

    <p>
        <label></label>
        <input type="submit" value="Entrar">
    </p>
</form>

</div>
</body>
</html>
```
