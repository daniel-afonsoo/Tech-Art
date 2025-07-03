<?php
require "../verifica.php";
require "../config/basedados.php";

// Habilitar relatórios de erro do MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Verifica se o utilizador está autenticado e é administrador
if ($_SESSION["autenticado"] != "administrador") {
    echo "<script>alert('Apenas administradores podem remover arquivos ou pastas.'); window.location.href='index.php';</script>";
    exit;
}

// Obtém os parâmetros da URL
$id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
$pasta = isset($_GET["pasta"]) ? $_GET["pasta"] : null;

// Função para excluir uma pasta e o seu conteúdo
function deletarPasta($caminho) {
    if (!is_dir($caminho)) return;
    $files = array_diff(scandir($caminho), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$caminho/$file";
        if (is_dir($filePath)) {
            deletarPasta($filePath);
        } else {
            unlink($filePath);
        }
    }
    rmdir($caminho);
}

try {
    // Remover ficheiro
    if ($id) {
        // Buscar o arquivo na base de dados
        $sql = "SELECT caminho FROM documentos_frontoffice WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($caminho);
        $stmt->fetch();
        $stmt->close();

        if ($caminho && file_exists($caminho)) {
            unlink($caminho); // Remove o ficheiro do servidor
        }

        // Remove da base de dados
        $sql = "DELETE FROM documentos_frontoffice WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Ficheiro removido com sucesso!'); window.location.href='index.php';</script>";
        exit;
    }

    // Remover pasta e todos os seus ficheiros
    if ($pasta) {
        $uploadsDir = "uploads/";
        $pastaPath = $uploadsDir . $pasta;

        // Busca todos os arquivos da pasta na base de dados
        $sql = "SELECT id, caminho FROM documentos_frontoffice WHERE caminho LIKE ?";
        $stmt = $conn->prepare($sql);
        $likePattern = $uploadsDir . $pasta . "/%";
        $stmt->bind_param("s", $likePattern);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if (file_exists($row["caminho"])) {
                unlink($row["caminho"]); // Remove o ficheiro do servidor
            }

            // Remove da base de dados
            $deleteSql = "DELETE FROM documentos_frontoffice WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $row["id"]);
            $deleteStmt->execute();
            $deleteStmt->close();
        }

        $stmt->close();

        // Remover a pasta do sistema de ficheiros
        deletarPasta($pastaPath);

        echo "<script>alert('Pasta e todos os seus arquivos foram removidos com sucesso!'); window.location.href='index.php';</script>";
        exit;
    }

    $conn->close();
    echo "<script>alert('Nenhuma ação foi executada.'); window.location.href='index.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('Erro: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='index.php';</script>";
}
?>