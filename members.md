## _members.php_

O arquivo `_members.php_` permite que os usuários encontrem outros membros e decidam adicioná-los como amigos ou removê-los, caso já estejam conectados. O programa opera em **dois modos**:

1. **Listagem de membros:** Exibe todos os usuários cadastrados, destacando seus relacionamentos com o usuário atual.
2. **Visualização de perfil:** Mostra detalhes específicos de um usuário selecionado.

---

### Visualizando o perfil de um usuário

O sistema primeiro verifica se a variável `view`, obtida do array `$_GET`, contém um valor. Se existir, significa que um usuário deseja visualizar o perfil de outro membro. O programa então:

- Usa a função `showProfile()` para exibir os detalhes do perfil.
- Oferece **links** para os amigos e mensagens do usuário.
- **Sanitiza a URL** contra entradas maliciosas, garantindo segurança.

---

### Adicionar e remover amigos

O código testa os parâmetros `$_GET['add']` e `$_GET['remove']`:

- Se **`add`** contiver um valor, o nome de usuário correspondente será **adicionado** à lista de amigos.
- Se **`remove`** contiver um valor, o usuário será **removido** da tabela de amizades.

A interação com a tabela `_friends_` do MySQL é feita utilizando **instruções preparadas** e **marcadores de posição**, garantindo segurança contra ataques de SQL Injection.

---

### Listando todos os membros

O programa executa uma **consulta SQL** para recuperar todos os nomes de usuário registrados. Em seguida, um loop `while` percorre os resultados e determina a relação de cada membro com o usuário atual:

- `$t1 ≠ 0`: O usuário **segue** outro membro.
- `$t2 ≠ 0`: Outro membro **segue** o usuário.
- Se ambos os valores forem diferentes de zero, os membros são **amigos em comum**.

Com base nesses relacionamentos, o sistema exibe:

- **Ícones indicativos**:

  - **⇔** → Amigos em comum.
  - **←** → O usuário segue um membro.
  - **→** → Um membro segue o usuário.

- **Links de interação**:

  - Se o usuário **ainda não segue** um membro, pode clicar em **“Seguir”**.
  - Se o membro **já segue** o usuário, um link **“Retribuir amizade”** é exibido.
  - Caso um usuário **já esteja seguindo** alguém, pode selecionar **“Deixar de seguir”** para encerrar a conexão.

---

```php
<?php
// Exemplo 09: members.php - Página de membros do sistema
require_once 'header.php';

// Verifica se o usuário está logado, se não, encerra a execução
if (!$loggedin) die("</div></body></html>");

// Obtém o nome do usuário da sessão
$user = $_SESSION['user'];

// Se o parâmetro 'view' estiver presente na URL
if (isset($_GET['view'])) {
    $view = $_GET['view'];
    $view_html_entities = htmlentities($view); // Prevenção contra XSS

    // Define o título do perfil
    if ($_GET['view'] === $user) {
        $name = "Seu";
    } else {
        $name = "Perfil de $view_html_entities";
    }

    // Exibe o perfil e mensagens do usuário
    echo "<h3>$name</h3>";
    showProfile($view, $pdo);
    echo "<a class='button' href='messages.php?view=$view_html_entities'>Ver mensagens de $name</a>";
    die("</div></body></html>");
}

// Lógica para adicionar/remover amigos
if (isset($_GET['add'])) {
    // Verifica se já não são amigos
    $stmt = $pdo->prepare('SELECT * FROM friends WHERE user=? AND friend=?');
    $stmt->execute([$_GET['add'], $user]);
    if (!$stmt->rowCount()) {
        // Adiciona a amizade no banco de dados
        $stmt = $pdo->prepare("INSERT INTO friends VALUES (?, ?)");
        $stmt->execute([$_GET['add'], $user]);
    }
} elseif (isset($_GET['remove'])) {
    // Remove a amizade do banco de dados
    $stmt = $pdo->prepare('DELETE FROM friends WHERE user=? AND friend=?');
    $stmt->execute([$_GET['remove'], $user]);
}
?>
      <p><strong>Outros membros</strong></p>
      <ul>
<?php
// Lista todos os membros do sistema
$stmt = $pdo->prepare("SELECT user FROM members ORDER BY user");
$stmt->execute();

// Se não houver outros membros
if (!$stmt->rowCount()) {
    echo '<li>Não há outros membros</li>';
}

// Para cada membro encontrado
while ($row = $stmt->fetch()) {
    // Ignora o próprio usuário
    if ($row['user'] === $user) continue;

    $rowuser_html_entities = htmlentities($row['user']); // Prevenção contra XSS

    // Link para o perfil do membro
    echo "<li><a href='members.php?view=$rowuser_html_entities'>$rowuser_html_entities</a>";

    $follow = "seguir"; // Texto padrão para o botão

    // Verifica relações de amizade
    $stmt2 = $pdo->prepare('SELECT * FROM friends WHERE user=? AND friend=?');

    // Verifica se o membro segue o usuário atual
    $stmt2->execute([$row['user'], $user]);
    $t1 = $stmt2->rowCount();

    // Verifica se o usuário atual segue o membro
    $stmt2->execute([$user, $row['user']]);
    $t2 = $stmt2->rowCount();

    // Exibe o status da relação
    if (($t1 + $t2) > 1) {
        echo " &harr; é um amigo mútuo";
    } elseif ($t1) {
        echo " &larr; você está seguindo";
    } elseif ($t2) {
        echo " &rarr; está seguindo você";
        $follow = "recip"; // Texto alternativo para o botão
    }

    // Exibe botão de ação (seguir/parar de seguir)
    if (!$t1) {
        echo " [<a href='members.php?add=$rowuser_html_entities'>$follow</a>]";
    } else {
        echo " [<a href='members.php?remove=$rowuser_html_entities'>deixar de seguir</a>]";
    }
}
?>
      </ul>
    </div>
  </body>
</html>
```
