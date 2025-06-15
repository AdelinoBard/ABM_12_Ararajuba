## _friends.php_

O módulo `_friends.php_` exibe os amigos e seguidores de um usuário, consultando a tabela `_friends_` do banco de dados de maneira similar ao arquivo `_members.php_`, porém **focado em um único usuário**. O sistema organiza os dados de relacionamento em três categorias:

1. **Amigos em comum**: Usuários que seguem e são seguidos pelo usuário atual.
2. **Seguidores**: Usuários que seguem o usuário, mas não são seguidos de volta.
3. **Seguindo**: Usuários que estão sendo seguidos pelo usuário, mas que não seguem de volta.

---

### Organização dos Relacionamentos

- **Todos os seguidores** são armazenados no array `$followers`.
- **Todos os usuários seguidos** são armazenados no array `$following`.

Para identificar **amigos em comum**, utilizamos:

```php
$mutual = array_intersect($followers, $following);
```

A função `array_intersect()` retorna um novo array contendo apenas os membros que estão **presentes em ambos os arrays**.

Depois, para remover os amigos em comum dos arrays **seguidores** e **seguindo**, aplicamos `array_diff()`:

```php
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
```

Essa separação permite que `$mutual` contenha **apenas amigos em comum**, `$followers` retenha **somente os seguidores**, e `$following` contenha **apenas aqueles que o usuário segue**.

---

### Exibição dos Dados

Com os arrays organizados, a exibição das categorias de amizade se torna simples e intuitiva. A função `sizeof()` (equivalente a `count()`) é usada para verificar se há elementos antes de exibir os grupos.

- **Personalização da interface**: O código usa as variáveis `$name1`, `$name2` e `$name3` para diferenciar quando um usuário está visualizando sua **própria lista de amigos**. Isso permite utilizar frases como **"Your friends"** e **"You are following"**, tornando a experiência mais natural.

- **Expansão opcional**: Caso deseje exibir **informações detalhadas dos perfis**, basta **descomentar** a linha correspondente no código.

---

```php
<?php // Example 10: friends.php

require_once 'header.php';

// Encerra a execução se o usuário não estiver logado
if (!$loggedin) die("</div></body></html>");

$user = $_SESSION['user']; // Usuário logado

// Determina o perfil a ser visualizado (o próprio ou outro usuário)
$view = isset($_GET['view']) ? $_GET['view'] : $user;
$view_html = htmlentities($view);

// Define os pronomes e nomes de exibição, dependendo se é o próprio perfil
if ($view === $user) {
  $name1 = $name2 = "Your";
  $name3 = "You are";
} else {
  $name1 = "<a href='members.php?view=$view_html'>$view_html</a>'s";
  $name2 = "$view_html's";
  $name3 = "$view_html is";
}

// (opcional) Exibe o perfil do usuário visualizado
// showProfile($view);

// Inicializa os arrays de seguidores e seguidos
$followers = $following = [];

// Pega quem segue $view (usuários que seguem este perfil)
$stmt = $pdo->prepare('SELECT * FROM friends WHERE user=?');
$stmt->execute([$view]);
while ($row = $stmt->fetch()) {
  $followers[] = $row['friend'];
}

// Pega quem $view está seguindo (os perfis que ele segue)
$stmt = $pdo->prepare('SELECT * FROM friends WHERE friend=?');
$stmt->execute([$view]);
while ($row = $stmt->fetch()) {
  $following[] = $row['user'];
}

// Determina amizades mútuas e remove-as dos arrays de seguidores/seguidos
$mutual = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);

$hasFriends = false;

echo "<br>";

// Exibe os amigos mútuos
if (count($mutual)) {
  echo "<span class='subhead'>$name2 mutual friends</span><ul>";
  foreach ($mutual as $friend) {
    $f_html = htmlentities($friend);
    echo "<li><a href='members.php?view=$f_html'>$f_html</a></li>";
  }
  echo "</ul>";
  $hasFriends = true;
}

// Exibe os seguidores
if (count($followers)) {
  echo "<span class='subhead'>$name2 followers</span><ul>";
  foreach ($followers as $friend) {
    $f_html = htmlentities($friend);
    echo "<li><a href='members.php?view=$f_html'>$f_html</a></li>";
  }
  echo "</ul>";
  $hasFriends = true;
}

// Exibe os seguidos
if (count($following)) {
  echo "<span class='subhead'>$name3 following</span><ul>";
  foreach ($following as $friend) {
    $f_html = htmlentities($friend);
    echo "<li><a href='members.php?view=$f_html'>$f_html</a></li>";
  }
  echo "</ul>";
  $hasFriends = true;
}

// Caso não haja nenhuma conexão
if (!$hasFriends)
  echo "<br>You don't have any friends yet.";

?>
</div>
</body>
</html>
```
