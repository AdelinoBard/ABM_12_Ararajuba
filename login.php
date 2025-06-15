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