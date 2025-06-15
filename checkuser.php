<?php
// Example 06: checkuser.php

require_once 'functions.php'; // Inclui funções auxiliares, como a conexão com o banco de dados via $pdo

// Verifica se o campo 'user' foi enviado via POST
if (isset($_POST['user'])) {

  // Sanitize do nome de usuário para prevenir XSS ao exibir na tela
  $user_input = trim($_POST['user']); // Remove espaços extras
  $user_html_entities = htmlentities($user_input); // Codifica caracteres especiais HTML

  // Prepara consulta SQL usando prepared statements (protege contra SQL Injection)
  $stmt = $pdo->prepare('SELECT * FROM members WHERE user = ?');

  // Executa a query com o valor do nome de usuário
  $stmt->execute([$user_input]);

  // Verifica se encontrou algum registro com o nome de usuário fornecido
  if ($stmt->rowCount()) {
    // Nome de usuário já existe no banco de dados
    echo "<span class='taken'>&nbsp;&#x2718; O nome de usuário '$user_html_entities' já está em uso.</span>";
  } else {
    // Nome de usuário está disponível
    echo "<span class='available'>&nbsp;&#x2714; O nome de usuário '$user_html_entities' está disponível.</span>";
  }
}
?>