## _logout.php_

O **encerramento de sessão** é o último passo da experiência do usuário na rede social. `_logout.php_` é responsável por **finalizar a sessão**, **remover dados temporários** e **excluir cookies associados**.

### Funcionamento

1. **Destruição da Sessão**: `session_destroy()` é chamado para eliminar todos os dados da sessão ativa.
2. **Remoção de Cookies**: Se houver cookies de autenticação armazenados, eles são apagados.
3. **Redirecionamento**: O usuário é enviado para `_index.php_` via `header("Location: index.php")`.

**Nota**: Se um usuário não estiver logado, o redirecionamento ocorrerá da mesma forma, sem processamento adicional.

---

```php
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
```
