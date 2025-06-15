## _messages.php_

O módulo `_messages.php_` gerencia a troca de mensagens entre usuários, permitindo o envio de mensagens **privadas ou públicas**.

### Publicação de Mensagens

O programa verifica se um texto foi enviado através da variável `$_POST['text']`. Se houver conteúdo:

- A mensagem é armazenada na tabela `_messages_`.
- O valor de `pm` é registrado para indicar a **visibilidade** da mensagem:
  - `0` → **Mensagem pública** (visível a todos os usuários).
  - `1` → **Mensagem privada** (visível apenas para o remetente e destinatário).

---

### Interface de Usuário

Após o processamento da mensagem, o sistema exibe:

- **Perfil do usuário**.
- **Formulário de envio** com opção de selecionar **mensagem privada ou pública**.
- **Lista de mensagens** já publicadas.

**Regras de Visibilidade**:

- Mensagens **públicas** podem ser lidas por qualquer usuário.
- Mensagens **privadas** só podem ser vistas pelo **remetente e destinatário**.
- Mensagens privadas são precedidas pela palavra _whispered_ e exibidas em **itálico**.

---

### Atualização e Gerenciamento de Mensagens

O programa fornece **links para atualizar a página de mensagens**, garantindo que novas interações sejam exibidas sem necessidade de recarregar manualmente.

Os usuários que acessam suas **próprias mensagens** recebem **opções para excluir** aquelas que não desejam manter.

**Personalização**: O código usa `$name1` e `$name2` para ajustar a exibição do nome, garantindo que, ao visualizar seu próprio perfil, a palavra **"Seu"** apareça em vez do nome de usuário.

---

```php
<?php // Exemplo 11: messages.php

require_once 'header.php'; // Inclui o cabeçalho e configurações iniciais

if (!$loggedin)
  // Se o usuário não estiver logado, encerra o script e fecha as tags HTML
  die("</div></body></html>");

$user = $_SESSION['user']; // Obtém o nome de usuário logado da sessão

// Define a variável de visualização: se houver parâmetro 'view', usa ele, senão, usa o próprio usuário
$view = isset($_GET['view']) ? $_GET['view'] : $user;
$view_html_entities = htmlentities($view); // Escapa caracteres especiais para evitar XSS

// Se o formulário foi enviado com uma mensagem, insere no banco de dados
if (isset($_POST['text']) && $_POST['text'] !== "") {
  $stmt = $pdo->prepare('INSERT INTO messages VALUES(NULL, ?, ?, ?, ?, ?)');
  $stmt->execute([$user, $view, (int)$_POST['pm'], time(), $_POST['text']]);
}

if ($view !== "") {
  // Define os nomes para o cabeçalho das mensagens
  if ($view === $user)
    $name1 = $name2 = "Suas";
  else {
    $name1 = "<a href='members.php?view=$view_html_entities'>$view_html_entities</a>";
    $name2 = $view_html_entities;
  }

  echo "<h3>Mensagens de $name1</h3>";
  showProfile($view, $pdo); // Mostra o perfil do usuário visualizado
?>
  <!-- Formulário para postar nova mensagem -->
  <form method="post" action="messages.php?view=<?php echo $view_html_entities; ?>">
    <p>Escreva aqui para deixar uma mensagem:</p>
    <p>
      <input type="radio" name="pm" id="public" value="0" checked="checked">
      <label for="public">Pública</label>
      <input type="radio" name="pm" id="private" value="1">
      <label for="private">Privada</label>
    </p>
    <textarea name="text" cols="50"></textarea><br>
    <input type="submit" class="button" value="Enviar Mensagem">
  </form><br>
<?php
  date_default_timezone_set('UTC'); // Define o fuso horário para UTC

  // Se foi solicitada a exclusão de uma mensagem
  if (isset($_GET['erase'])) {
    $stmt = $pdo->prepare('DELETE FROM messages WHERE id=? AND recip=?');
    $stmt->execute([(int)$_GET['erase'], $user]);
  }

  // Recupera todas as mensagens para o usuário ou visualização atual
  $stmt = $pdo->prepare('SELECT * FROM messages WHERE recip=? ORDER BY time DESC');
  $stmt->execute([$view]);
  $num = $stmt->rowCount();

  // Exibe cada mensagem (se for pública ou acessível ao usuário)
  while ($row = $stmt->fetch()) {
    $pm = $row['pm'] === '1';
    $auth_html_entities = htmlentities($row['auth']);
    $message_html_entities = htmlentities($row['message']);
    $id_html_entities = htmlentities($row['id']);

    if (!$pm || $row['auth'] === $user || $row['recip'] === $user) {
      echo date('d/m/y H:i:', $row['time']); // Formato de data/hora brasileira
      echo " <a href='messages.php?view=$auth_html_entities'>$auth_html_entities</a> ";

      if (!$pm)
        echo "escreveu: &quot;$message_html_entities&quot; ";
      else
        echo "sussurrou: <span class='whisper'>&quot;$message_html_entities&quot;</span> ";

      // Se o destinatário for o usuário, permite apagar
      if ($row['recip'] === $user)
        echo "[<a href='messages.php?view=$view_html_entities&erase=$id_html_entities'>apagar</a>]";

      echo "<br>";
    }
  }
}

// Se não houver mensagens
if (!$num)
  echo "<br><span class='info'>Nenhuma mensagem até agora</span><br><br>";

// Link para atualizar as mensagens
echo "<br><a class='button' href='messages.php?view=$view_html_entities'>Atualizar mensagens</a>";
?>
</div>
</body>
</html>
```
