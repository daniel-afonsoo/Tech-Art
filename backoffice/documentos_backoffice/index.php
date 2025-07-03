<?php
require "../config/basedados.php";
require "../verifica.php";

// Buscar documentos agrupados por nome base e idioma (PT / EN)
$sql = "SELECT * FROM documentos_backoffice ORDER BY nome_arquivo";
$result = $conn->query($sql);

$agrupado = [];

if ($result && $result->num_rows > 0) {
    while ($doc = $result->fetch_assoc()) {
        $nomeBase = preg_replace('/^(PT|EN)_/', '', pathinfo($doc['nome_arquivo'], PATHINFO_FILENAME));
        $lang = strtoupper(substr($doc['nome_arquivo'], 0, 2));
        $agrupado[$nomeBase][$lang] = [
            'caminho' => $doc['caminho'],
            'id' => $doc['id'], // Incluído o ID para remoção/edição
            'permissoes' => $doc['permissoes'] // Incluído as permissões para edição
        ];
    }
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

    .btn-danger, .btn-primary {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 5px;
    }
</style>

<!-- Conteúdo -->
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>📂 Documentos do BackOffice</h3>
        </div>
        <div class="card-body">

            <!-- Documentos Dinâmicos -->
            <table class="table table-bordered mb-4">
                <thead class="thead-light">
                    <tr>
                        <th style="width:100px;">Documento</th>
                        <th style="width:110px;">Português</th>
                        <th style="width:250px;">Inglês</th>
                        <th style="width:150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($agrupado)): ?>
                        <?php foreach ($agrupado as $nome => $langs): ?>
                            <tr>
                                <td><?= htmlspecialchars($nome) ?></td>
                                <td>
                                    <?= isset($langs['PT']) ? "<a href='{$langs['PT']['caminho']}' target='_blank'>Português</a>" : '-' ?>
                                </td>
                                <td>
                                    <?= isset($langs['EN']) ? "<a href='{$langs['EN']['caminho']}' target='_blank'>Inglês</a>" : '-' ?>
                                </td>
                                <td>
                                    <?php if ($_SESSION["autenticado"] == "administrador"): ?>
                                        <?php if (isset($langs['PT'])): ?>
                                            <a href="remover.php?id=<?= $langs['PT']['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Tem certeza que deseja remover este documento?')">
                                                Remover
                                            </a>
                                            <a href="edit.php?id=<?= $langs['PT']['id'] ?>" 
                                               class="btn btn-sm btn-primary ml-2">
                                                Editar
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isset($langs['EN'])): ?>
                                            <a href="remover.php?id=<?= $langs['EN']['id'] ?>" 
                                               class="btn btn-sm btn-danger ml-2" 
                                               onclick="return confirm('Tem certeza que deseja remover este documento?')">
                                                Remover
                                            </a>
                                            <a href="edit.php?id=<?= $langs['EN']['id'] ?>" 
                                               class="btn btn-sm btn-primary ml-2">
                                                Editar
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Nenhum documento encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Botão de Upload -->
            <div class="d-flex justify-content-end">
                <a href="upload.php" class="btn btn-success">
                    <i class="fa fa-plus"></i>&nbsp; Adicionar Novo Documento
                </a>
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