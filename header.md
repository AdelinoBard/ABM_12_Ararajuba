## _header.php_

Para garantir uniformidade, todas as páginas do projeto precisam acessar o mesmo conjunto de recursos. Para isso, `_header.php_` centraliza essas definições e é incluído por outros arquivos. Além disso, carrega `_functions.php_`, permitindo que cada arquivo tenha acesso às funções essenciais por meio de um único `require_once`.

### Inicialização da Sessão

O arquivo `_header.php_` inicia uma sessão com `session_start()`, garantindo a persistência de determinados valores ao longo da navegação. Isso permite que um usuário seja identificado enquanto acessa diferentes páginas. No entanto, a sessão pode expirar após um período de inatividade.

Após a inicialização, `_functions.php_` é incluído e a variável `$userstr` recebe o valor padrão **“Welcome Guest”**.

### Identificação do Usuário

O código verifica se a variável de sessão `user` possui um valor atribuído:

- **Usuário autenticado:** A variável `$loggedin` é definida como `TRUE`. O nome de usuário é recuperado de `$_SESSION['user']`, sanitizado (`$user_html_entities`) e armazenado em `$userstr`.
- **Usuário não autenticado:** `$loggedin` é definido como `FALSE`.

### Estrutura da Página

Com `$loggedin` definido, o sistema gera a estrutura HTML comum a todas as páginas, incluindo:

- Folhas de estilo e ícones do Bootstrap
- Logotipo do projeto
- Saudação dinâmica baseada no status de login

### Exibição Condicional de Menus

Com base no valor de `$loggedin`, um bloco `if` determina quais opções de menu serão apresentadas:

- **Usuário não autenticado:** Exibe apenas as opções _Início_, _Cadastro_ e _Login_.
- **Usuário autenticado:** Exibe o menu completo com acesso a todas as funcionalidades do sistema.

### Estilização

Os estilos adicionais aplicados a `_header.php_` estão definidos no arquivo `_styles.css_`.

---

```php
<?php
session_start();
require_once 'functions.php';

$userstr = 'Bem-vindo Visitante';

if (isset($_SESSION['user'])) {
    $user_html_entities = htmlentities($_SESSION['user']);
    $loggedin = TRUE;
    $userstr  = "Conectado como: $user_html_entities";
} else {
    $loggedin = FALSE;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ararajuba Social: <?php echo $userstr; ?></title>

    <!-- Bootstrap & Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Ararajuba</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <?php if ($loggedin) { ?>
                        <li class="nav-item"><a class="nav-link" href="members.php">Membros</a></li>
                        <li class="nav-item"><a class="nav-link" href="friends.php">Amigos</a></li>
                        <li class="nav-item"><a class="nav-link" href="messages.php">Mensagens</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Editar Perfil</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Sair</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="signup.php">Cadastrar</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Entrar</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="container mt-4">
        <div class="text-center">
            <h1 class="fw-bold">Ararajuba</h1>
            <p class="lead"><?php echo $userstr; ?></p>
        </div>
    </main>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```
