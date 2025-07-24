<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] === false) {
    header("Location: ../login.php");
    exit;
}