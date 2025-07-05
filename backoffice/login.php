<?php
// login.php

require "config/basedados.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"], $_POST["password"])) {
    $login    = trim($_POST["login"]); // Nome ou email
    $password = $_POST["password"];

    // Validação simples
    if ($login === '' || $password === '') {
        echo "Preencha todos os campos.";
        exit;
    }

    //
    // 1) Verifica ADMINISTRADOR (só por email)
    //
    $sql  = "SELECT id, password
               FROM administradores
              WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION["autenticado"] = 'administrador';
            $_SESSION["adminid"]     = $row["id"];
            header("Location: Views/index.php");
            exit;
        }
    }

    //
    // 2) Verifica INVESTIGADORES (email OU nome, insensível a maiúsculas)
    //
    $sql  = "
      SELECT id, password, tipo, ultimologin
        FROM investigadores
       WHERE LOWER(email) = LOWER(?)
          OR LOWER(nome)  = LOWER(?)
       LIMIT 1
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $login, $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // Se for o primeiro login e quiser forçar troca de password
            if (is_null($row["ultimologin"])) {
                $_SESSION["resetpassword"] = true;
            }

            // Normaliza o tipo para minúsculas
            $tipoUtilizador = strtolower($row["tipo"]);
            $_SESSION["autenticado"] = $tipoUtilizador;
            $_SESSION["userid"]      = $row["id"];

            header("Location: Views/index.php");
            exit;
        }
    }

    // Se chegar aqui, falhou
    echo "Login ou palavra-passe errados!<br><br>";
    unset($_SESSION["autenticado"]);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>TECHN&ART Login</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link href="style.css" rel="stylesheet">
</head>
<body>
  <div class="wrapper fadeInDown">
    <div id="formContent">
      <form action="login.php" class="mt-3" method="post">
        <input type="text" id="login" class="fadeIn first" name="login" placeholder="Nome ou Email">
        <input type="password" id="password" class="fadeIn second" name="password" placeholder="Senha">
        <div style="margin: 5px; width: 85%; padding: 0 80px; text-align: center; display: inline-block;">
          <button type="submit" class="fadeIn third btn btn-primary btn-block" style="background-color: #56baed; border: none; color: white;">
            Login
          </button>
          <button type="button" onclick="window.location.href = '../tecnart/index.php'" class="fadeIn fourth btn btn-danger btn-block">
            Sair
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
