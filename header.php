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
