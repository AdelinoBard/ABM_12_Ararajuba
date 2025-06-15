## _profile.php_

Após o cadastro e login, os usuários podem configurar seus perfis por meio do arquivo `_profile.php_`. Este módulo contém lógica para **carregar, redimensionar e melhorar a nitidez de imagens**, além de possibilitar a publicação de um texto personalizado.

### Estrutura do Formulário

O formulário HTML no final do código segue um padrão familiar, mas traz um diferencial importante:

- Utiliza `enctype="multipart/form-data"` para permitir o envio de **texto e imagem** simultaneamente.
- Conta com um campo de entrada `file`, que exibe um botão **“Navegar”** para que o usuário selecione um arquivo a ser enviado.

Ao enviar o formulário, o código no início do programa é executado. A primeira verificação garante que o usuário esteja logado antes de exibir a página.

---

## Adicionando o texto “Sobre mim”

O programa verifica se `$_POST['text']` contém um valor:

- Se houver conteúdo, todas as **sequências longas de espaços em branco** (incluindo quebras de linha) são substituídas por espaços simples.
- Caso já exista um perfil cadastrado, o texto **"Sobre mim"** é atualizado no banco de dados; caso contrário, um novo registro é criado.

**Otimização:** Duas **instruções preparadas** são `prepare()`, mas apenas uma chamada `execute()` é necessária, pois ambas utilizam os mesmos dados (`$text` e `$user`).

Se nenhum texto for enviado, o banco de dados é consultado para preencher previamente o `<textarea>`, permitindo que o usuário edite seu perfil. A função `htmlentities()` é aplicada para sanitizar a saída contra **ataques XSS**.

---

## Adicionando uma Imagem de Perfil

O sistema verifica a variável `$_FILES` para detectar se uma imagem foi carregada:

- Caso positivo, o nome do arquivo a ser salvo é gerado dinamicamente com base no nome de usuário (`$user.jpg`).
- Apenas **arquivos `.jpeg`, `.png` ou `.gif`** são aceitos.
- A função `getimagesize()` retorna as **dimensões** e **tipo da imagem**, permitindo validar seu formato corretamente.

Uma maneira eficiente de atribuir os valores retornados por `getimagesize()` às variáveis `$w`, `$h` e `$type`:

```php
list($w, $h, $type) = getimagesize($saveto);
```

---

### Aviso de Segurança

Não utilize `$_FILES['type']` para validar o formato da imagem, pois esse dado pode ser **modificado por um invasor**. A maneira correta é verificar o tipo real da imagem utilizando `getimagesize()`.

Caso a imagem seja válida, o arquivo é carregado em **formato bruto**, permitindo processamento pelo PHP. Se o formato não for suportado, o sinalizador `$typeok` é definido como `FALSE`, impedindo a execução da lógica final do upload.

---

## Processando e Otimizando a Imagem

Após o upload, a imagem é redimensionada para um máximo de `100px`, mantendo sua proporção original:

1. **Cálculo das novas dimensões** (`$tw` e `$th`) com base em `$max = 100`.
2. **Criação de uma tela em branco** (`imagecreatetruecolor()`).
3. **Reamostragem da imagem** (`imagecopyresampled()`).
4. **Ajuste de nitidez** para compensar qualquer perda de qualidade (`imageconvolution()`).
5. **Salvamento do arquivo `.jpeg`** no caminho definido em `$saveto`.
6. **Liberação de memória** usando `imagedestroy()`, removendo as versões original e redimensionada da imagem.

Se desejar miniaturas **maiores ou menores**, basta ajustar o valor de `$max`.

---

## Exibição do Perfil Atual

Para que o usuário visualize seu perfil antes de editá-lo, a função `showProfile()` de `_functions.php_` é chamada antes da renderização do formulário.

Caso haja uma **imagem de perfil**, ela recebe estilos CSS que adicionam:

- **Borda**
- **Sombra**
- **Margem lateral**, garantindo uma separação visual adequada do texto.

A `<textarea>` também é preenchida previamente com o texto salvo, facilitando a edição.

---

```php
<?php // Exemplo 08: profile.php
  require_once 'header.php'; // Inclui o cabeçalho com sessão e conexões

  if (!$loggedin)
    die("</div></body></html>"); // Se não estiver logado, encerra a execução

  $user = $_SESSION['user']; // Recupera o usuário logado

  echo "<h3>Seu Perfil</h3>"; // Título em pt-br

  // Busca o perfil do usuário no banco de dados
  $stmt = $pdo->prepare('SELECT * FROM profiles WHERE user=?');
  $stmt->execute([$user]);

  if (isset($_POST['text'])) { // Se o usuário enviou novo texto
    $text = preg_replace('/\s\s+/', ' ', $_POST['text']); // Remove espaços excessivos
    $text_html_entities = htmlentities($_POST['text']);    // Escapa HTML

    // Atualiza ou insere o texto do perfil
    if ($stmt->rowCount())
      $stmt2 = $pdo->prepare('UPDATE profiles SET text=:text WHERE user=:user');
    else
      $stmt2 = $pdo->prepare('INSERT INTO profiles VALUES(:user, :text)');
    $stmt2->execute([':text' => $text, ':user' => $user]);
  } else {
    // Caso não tenha envio de texto, carrega o texto salvo, se existir
    if ($stmt->rowCount()) {
      $row = $stmt->fetch();
      $text_html_entities = htmlentities($row['text']);
    }
    else $text_html_entities = "";
  }

  if (isset($_FILES['image']['name'])) { // Se uma imagem foi enviada
    $saveto = "$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto); // Salva a imagem
    $typeok = TRUE;

    $info = getimagesize($saveto); // Verifica o tipo da imagem
    if ($info) {
      list($w, $h, $type) = $info;
      switch ($type) {
        case IMAGETYPE_GIF:  $src = imagecreatefromgif($saveto); break;
        case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($saveto); break;
        case IMAGETYPE_PNG:  $src = imagecreatefrompng($saveto); break;
        default:             $typeok = FALSE; break;
      }
    } else {
      $typeok = FALSE;
    }

    if ($typeok) {
      $max = 100; // Define o tamanho máximo em pixels
      $tw = $w;
      $th = $h;

      // Calcula nova largura e altura mantendo proporção
      if ($w > $h && $max < $w) {
        $th = $max / $w * $h;
        $tw = $max;
      } elseif ($h > $w && $max < $h) {
        $tw = $max / $h * $w;
        $th = $max;
      } elseif ($max < $w) {
        $tw = $th = $max;
      }

      // Redimensiona e aplica leve nitidez
      $tmp = imagecreatetruecolor($tw, $th);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      imageconvolution($tmp, [[-1, -1, -1], [-1, 16, -1], [-1, -1, -1]], 8, 0);
      imagejpeg($tmp, $saveto); // Salva a imagem final
      imagedestroy($tmp);
      imagedestroy($src);
    }
  }

  showProfile($user, $pdo); // Exibe o perfil do usuário formatado
?>

<!-- Formulário para editar dados e enviar imagem -->
<form method="post" action="profile.php" enctype="multipart/form-data">
  <h3>Digite ou edite seus dados e/ou envie uma imagem</h3>
  <textarea name="text" cols="50"><?php echo $text_html_entities; ?></textarea>
  <p>Imagem: <input type="file" name="image" size="14"></p>
  <input type="submit" class="button" value="Salvar Perfil">
</form>
</div>
</body>
</html>
```
