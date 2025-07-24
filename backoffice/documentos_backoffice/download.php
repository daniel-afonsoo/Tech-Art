<?php
require "../verifica_sessao.php"; // Garante que o utilizador está autenticado

$basePath = __DIR__ . "/../private_docs/";
$filename = basename($_GET['file'] ?? '');

if (!$filename || strpos($filename, '..') !== false) {
    http_response_code(400);
    exit("Nome de ficheiro inválido.");
}

$filepath = $basePath . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    exit("Ficheiro não encontrado.");
}

// Tipo de conteúdo: PDF (pode ajustar se usar outros tipos)
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"" . $filename . "\"");
header("Content-Length: " . filesize($filepath));
readfile($filepath);
exit;
