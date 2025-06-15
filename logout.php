<?php
// Exemplo 12: logout.php
// Página para encerrar a sessão do usuário

// Inclui o cabeçalho da página
require_once 'header.php';

// Verifica se há um usuário logado (sessão ativa)
if (isset($_SESSION['user'])) {
    // Destroi a sessão atual e redireciona para a página inicial
    destroySession();
    header('Location: index.php');
    exit(); // Importante para evitar execução adicional do script
} else {
    // Mensagem para quando não há usuário logado
    echo "<div class='center'>Você não pode sair porque não está logado</div>";
}
?>

    </div>
  </body>
</html>