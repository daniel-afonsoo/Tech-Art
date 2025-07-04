<?php
require "../verifica.php";
require "../config/basedados.php";

// Query simples para buscar todos os registros
$sql = "SELECT id, texto_pt, texto_en, titulo_pt, titulo_en
          FROM estrutura
         ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Estrutura Orgânica</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .table-title {
            background: rgb(71, 118, 165);
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
        img.fotografia {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .pagination a {
            margin: 0 2px;
        }
        .text-preview {
            max-width: 200px;
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="table-title d-flex justify-content-between align-items-center">
        <h2>Estrutura Orgânica</h2>
        <!-- Botão de adicionar -->
        <a href="add.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Adicionar Novo
        </a>
    </div>

    <!-- Tabela -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título (PT)</th>
                    <th>Título (EN)</th>
                    <th>Texto (PT)</th>
                    <th>Texto (EN)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['titulo_pt']) ?></td>
                        <td><?= htmlspecialchars($row['titulo_en']) ?></td>
                        <td>
                            <div class="text-preview" title="<?= htmlspecialchars($row['texto_pt']) ?>">
                                <?= htmlspecialchars(substr($row['texto_pt'], 0, 50)) ?>...
                            </div>
                        </td>
                        <td>
                            <div class="text-preview" title="<?= htmlspecialchars($row['texto_en']) ?>">
                                <?= htmlspecialchars(substr($row['texto_en'], 0, 50)) ?>...
                            </div>
                        </td>
                        <td style="min-width:180px;">
                            <a href="edit.php?id=<?= $row['id'] ?>" 
                               class="btn btn-primary mb-1">
                               Alterar
                            </a>
                            <a href="delete.php?id=<?= $row['id'] ?>" 
                               class="btn btn-danger mb-1"
                               onclick="return confirm('Tem certeza que deseja apagar este registro?');">
                               Apagar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhum registro encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>