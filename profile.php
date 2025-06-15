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