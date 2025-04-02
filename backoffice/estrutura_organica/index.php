<?php
require "../verifica.php";
require "../config/basedados.php";

// Filtro de pesquisa
$search = isset($_GET['search']) ? $_GET['search'] : '';


$perPage  = 10;
$page     = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start    = ($page - 1) * $perPage;
$searchQ  = '%' . $search . '%';


$sql = "SELECT id, chave , texto_pt, texto_en
          FROM estrutura_organica
         WHERE chave LIKE ?
         LIMIT $start, $perPage";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $searchQ);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Query para contar total de registros (para paginação)
$totalSql = "SELECT COUNT(*) 
               FROM estrutura_organica
              WHERE chave LIKE ?";
$stmtTotal = mysqli_prepare($conn, $totalSql);
mysqli_stmt_bind_param($stmtTotal, 's', $searchQ);
mysqli_stmt_execute($stmtTotal);
$totalResult = mysqli_stmt_get_result($stmtTotal);
$totalRows   = mysqli_fetch_row($totalResult)[0];
$totalPages  = ceil($totalRows / $perPage);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Estrutura Organica</title>
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

    <!-- Campo de pesquisa -->
    <div class="mb-3">
        <form method="GET" action="">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Pesquisar por nome" 
                       value="<?= htmlspecialchars($search) ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Chave</th>
                    <th>Texto (PT)</th>
                    <th>Texto (EN)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['chave']) ?></td>
                        <td><?= htmlspecialchars($row['texto_pt']) ?></td>
                        <td><?= htmlspecialchars($row['texto_en']) ?></td>
                        <td style="min-width:180px;">
                            <a href="edit.php?id=<?= $row['id'] ?>" 
                               class="btn btn-primary mb-1">
                               Alterar
                            </a>
                            <a href="delete.php?id=<?= $row['id'] ?>" 
                               class="btn btn-danger mb-1">
                               Apagar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="pagination">
            <!-- Botão "Anterior" -->
            <?php if ($page > 1): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" 
                   class="btn btn-secondary">
                   Anterior
                </a>
            <?php endif; ?>

            <!-- Páginas -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>" 
                   class="btn btn-light <?= ($i === $page) ? 'active' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Botão "Próximo" -->
            <?php if ($page < $totalPages): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" 
                   class="btn btn-secondary">
                   Próximo
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
