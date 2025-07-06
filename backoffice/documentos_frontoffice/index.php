<?php
require "../verifica.php";
require "../config/basedados.php";

// Habilitar relatórios de erro do MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Identificar perfil de utilizador
$tipoUtilizador = $_SESSION["autenticado"] ?? null;

// Buscar documentos do FrontOffice
$sql = "SELECT id, nome_arquivo, caminho, pasta
        FROM documentos_frontoffice
        ORDER BY pasta, nome_arquivo";
$result = $conn->query($sql);

// Agrupar arquivos por pasta
$arquivosPorPasta = [];
while ($row = $result->fetch_assoc()) {
    $arquivosPorPasta[$row['pasta']][] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Documentos do FrontOffice</title>
    <!-- Bootstrap CSS & JS + dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .folder-header { cursor: pointer; }
        .file { margin-left: 2rem; display: flex; align-items: center; margin-bottom: .5rem; }
        .actions { margin-left: 1rem; }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">📂 Documentos do FrontOffice</h3>
            <?php if ($tipoUtilizador === 'administrador'): ?>
            <div>
                <a href="upload.php" class="btn btn-success mr-2">
                    <i class="fa fa-plus"></i> Adicionar Novo Documento
                </a>
            </div>
            <?php endif; ?>
                <?php if ($tipoUtilizador === 'administrador'): ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        🗑️ Remover Pasta
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php foreach (array_keys($arquivosPorPasta) as $pasta): ?>
                            <a class="dropdown-item" href="remover.php?pasta=<?= urlencode($pasta) ?>"
                               onclick="return confirm('Confirma remoção da pasta «<?= htmlspecialchars(addslashes($pasta)) ?>»?');">
                                <?= htmlspecialchars($pasta) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php foreach ($arquivosPorPasta as $pasta => $arquivos): 
                $folderId = md5($pasta);
            ?>
                <div class="mb-2">
                    <h5 class="folder-header" onclick="toggleFolder('<?= $folderId ?>')">
                        📁 <?= htmlspecialchars($pasta) ?>
                    </h5>
                    <div id="<?= $folderId ?>" class="hidden">
                        <?php foreach ($arquivos as $arquivo): ?>
                            <div class="file">
                                📄 <a href="<?= htmlspecialchars($arquivo['caminho']) ?>" download><?= htmlspecialchars($arquivo['nome_arquivo']) ?></a>
                                <?php if ($tipoUtilizador === 'administrador'): ?>
                                    <span class="actions">
                                        <a href="remover.php?id=<?= $arquivo['id'] ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Deseja remover este documento?');">Remover</a>
                                        <a href="edit.php?id=<?= $arquivo['id'] ?>" class="btn btn-sm btn-primary ml-2">Editar</a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($arquivosPorPasta)): ?>
                <p class="text-muted">Nenhum documento encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleFolder(id) {
        const el = document.getElementById(id);
        el.classList.toggle('hidden');
    }
</script>
</body>
</html>