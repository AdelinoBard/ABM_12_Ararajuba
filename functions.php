<?php // functions.php - Exemplo aprimorado

// Configurações do banco de dados
$dbhost = 'localhost';
$db     = 'ararajuba';           // Nome atualizado do banco de dados
$dbuser = 'abm';                 // Novo usuário do banco conforme instrução
$dbpass = 'B@rd';                // Nova senha
$chrset = 'utf8mb4';             // Charset moderno e seguro para suporte a emojis e unicode

// DSN (Data Source Name) para conexão via PDO
$dbattr = "mysql:host=$dbhost;dbname=$db;charset=$chrset";

// Array de opções para a instância PDO
$opts = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna os dados como array associativo
  PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa prepares nativos do MySQL (mais seguros)
];

try {
  // Criação da conexão PDO com as configurações acima
  $pdo = new PDO($dbattr, $dbuser, $dbpass, $opts);
} catch (PDOException $e) {
  // Em caso de erro na conexão, relança a exceção
  throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Função para destruir a sessão do usuário de forma segura
function destroySession()
{
  $_SESSION = []; // Limpa a array de sessão

  // Remove o cookie da sessão, se existir
  if (session_id() !== "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 2592000, '/');
  }

  session_destroy(); // Destrói a sessão no servidor
}

// Exibe o perfil do usuário com base no nome e banco de dados
function showProfile($user, $pdo)
{
  // Se existir um arquivo de imagem com o nome do usuário, exibe como avatar
  if (file_exists("$user.jpg")) {
    echo "<img src='$user.jpg' style='float:left;'>";
  }

  // Consulta o perfil na tabela `profiles` pelo nome do usuário
  $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user=?");
  $stmt->execute([$user]);

  // Se houver resultado, exibe o texto do perfil; caso contrário, uma mensagem padrão
  $row = $stmt->fetch();
  if ($row) {
    echo htmlentities($row['text']) . "<br style='clear:left;'><br>";
  } else {
    echo "<p>Nothing to see here, yet</p><br>";
  }
}
?>