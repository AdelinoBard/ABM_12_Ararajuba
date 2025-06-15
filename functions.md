## _functions.php_

O arquivo `_functions.php_` contém as funções principais do projeto, além das configurações de conexão com o banco de dados. Para simplificar a estrutura, os detalhes de login foram incorporados diretamente neste arquivo, em vez de separados em outro.

Nas quatro primeiras linhas de código, são definidos o host, o nome do banco de dados, o usuário e a senha de acesso.

### Configuração do Banco de Dados

Para utilizar este código em seu projeto, primeiro crie um banco de dados. Neste exemplo, o banco foi nomeado `_ararajuba_`.

O usuário criado para acesso foi `_abm_`, com os seguintes privilégios:

```sql
CREATE USER 'abm'@'localhost' IDENTIFIED BY 'B@rd';
GRANT ALL PRIVILEGES ON ararajuba.* TO 'abm'@'localhost';
```

**Atenção:** Esta senha não é segura e deve ser substituída em um ambiente de produção.

### Pré-requisitos

Para que este código funcione corretamente, certifique-se de:

1. Criar o banco de dados conforme especificado na variável `$db`.
2. Conceder acesso ao usuário `$dbuser` com a senha definida em `$dbpass`.

### Funcionalidades Principais

O projeto utiliza duas funções essenciais:

- `destroySession`: Encerra a sessão do usuário, eliminando seus dados de autenticação e desconectando-o do sistema.
- `mostrarPerfil`: Busca uma imagem de perfil nomeada como `<user.jpg>` (onde `<user>` corresponde ao nome de usuário atual). Caso a encontre, a exibe juntamente com qualquer texto "sobre mim" salvo pelo usuário.

Implementamos tratamento de erros em todas as funções que exigem validação, garantindo que falhas como erros tipográficos sejam detectadas e gerem mensagens adequadas. No entanto, para uso em servidores de produção, recomenda-se desenvolver rotinas de tratamento de erro mais amigáveis ao usuário.

### Considerações

Este projeto utiliza **PDO** em vez das antigas extensões `mysql` ou `mysqli`, proporcionando maior segurança e flexibilidade na manipulação do banco de dados.

Além disso, todas as consultas SQL empregam **instruções preparadas** e **marcadores de posição**, protegendo a aplicação contra ataques de injeção de SQL.

**Lembre-se:** Para referenciar o banco de dados MySQL via `PDO`, a função `showProfile` requer um parâmetro `$pdo`, que deve ser passado ao chamar a função.

---

```php
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
```
