<?php
// Verifica se o utilizador tem permissão para aceder às notícias
if ($_SESSION["autenticado"] != "administrador") {
    header("Location: index.php");
    exit;
}
?>