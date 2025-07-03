<?php
require "../verifica.php";
require "../config/basedados.php";

// Consultar documentos do FrontOffice
$sql = "SELECT id, nome_arquivo, caminho, pasta
        FROM documentos_frontoffice 
        ORDER BY pasta, nome_arquivo";
$result = $conn->query($sql);

// Organizar os arquivos por pasta
$arquivosPorPasta = [];
while ($row = $result->fetch_assoc()) {
    $arquivosPorPasta[$row["pasta"]][] = $row;
}

$conn->close();
?>

<!-- Estilos e Scripts -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css ">
<script src="https://code.jquery.com/jquery-3.5.1.min.js "></script>

<style>
    .folder {
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
    }

    .folder:hover {
        text-decoration: underline;
    }

    .file {
        margin-left: 30px;
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .actions {
        margin-left: 10px;
    }

    .hidden {
        display: none;
    }

    .btn-success {
        padding: 8px 15px;
        font-size: 14px;
        border-radius: 5px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- Container Principal -->
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>📂 Documentos do FrontOffice</h3>
        </div>
        <div class="card-body">
            <!-- Botões de Ação -->
            <div class="mb-3 d-flex justify-content-between">
                <a href="upload.php" class="btn btn-success">
                    <i class="fa fa-plus"></i> Adicionar Novo Documento
                </a>

                <div class="d-flex">
                    <form action="remover.php" method="GET" onsubmit="return confirm('Tem certeza que deseja remover esta pasta e todos os seus arquivos?');" class="d-flex">
                        <select name="pasta" class="form-control w-auto">
                            <option value="">Selecione uma pasta</option>
                            <?php foreach ($arquivosPorPasta as $pasta => $arquivos): ?>
                                <option value="<?= htmlspecialchars($pasta) ?>"><?= htmlspecialchars($pasta) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-danger ml-2">
                            <i class="fa fa-trash"></i> Remover Pasta
                        </button>
                    </form>
                </div>
            </div>

            <!-- Estrutura em Árvore -->
            <div>
                <?php if (!empty($arquivosPorPasta)): ?>
                    <?php foreach ($arquivosPorPasta as $pasta => $arquivos): ?>
                        <div class="folder" onclick="toggleFolder('<?= md5($pasta) ?>')">
                            📁 <?= htmlspecialchars($pasta) ?>
                        </div>
                        <div id="<?= md5($pasta) ?>" class="hidden">
                            <?php foreach ($arquivos as $arquivo): ?>
                                <div class="file">
                                    📄 <a href="<?= $arquivo['caminho'] ?>" download><?= htmlspecialchars($arquivo['nome_arquivo']) ?></a>
                                    <span class="actions">
                                        <?php if ($_SESSION["autenticado"] == "administrador"): ?>
                                            <a href="remover.php?id=<?= $arquivo['id'] ?>" 
                                               class="btn btn-sm btn-danger remove-btn" 
                                               onclick="return confirm('Tem certeza que deseja remover este documento?')">
                                                Remover
                                            </a>
                                              <a href="edit.php?id=<?= $arquivo['id'] ?>" 
                                               class="btn btn-sm btn-primary ml-2">
                                                Editar
                                            </a>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum documento encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFolder(id) {
        var folder = document.getElementById(id);
        if (folder.style.display === "none" || folder.style.display === "") {
            folder.style.display = "block";
        } else {
            folder.style.display = "none";
        }
    }
</script>