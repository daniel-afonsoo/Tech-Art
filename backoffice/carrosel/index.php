<?php
require "../verifica.php";
require "../config/basedados.php";



$search    = $_GET['search'] ?? '';
$perPage   = 10;
$searchSQL = '%' . $search . '%';
$page      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start     = ($page - 1) * $perPage;

// Consulta principal com pesquisa no campo 'titulo'
$sql = "SELECT id, titulo, subtitulo, imagem
        FROM carousel
        WHERE titulo LIKE '$searchSQL'
        LIMIT $start, $perPage";
$result = mysqli_query($conn, $sql);

// Conta total de registos para paginação
$totalSql    = "SELECT COUNT(*) FROM carousel WHERE titulo LIKE '$searchSQL'";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows   = mysqli_fetch_row($totalResult)[0];
$totalPages  = ceil($totalRows / $perPage);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Carrossel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        .btn-primary {
            background: blue;
            border: none;
        }
        .btn-danger {
            background: red;
            border: none;
        }
        .btn-success {
            background: green;
            border: none;
        }
        .btn-warning {
            background: orange;
            border: none;
        }
        .table-responsive {
            margin-top: 20px;
        }
        textarea {
            width: 100%;
            resize: none;
        }
        img.fotografia {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        
    </style>
</head>
<body>

<div class="container mt-4">
    <!-- Título e botão de adicionar -->
    <div class="table-title d-flex justify-content-between align-items-center">
        <h2>Carrossel</h2>
        <a href="add.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Adicionar Novo
        </a>
    </div>

    <!-- Formulário de pesquisa -->
    <div class="mb-3">
        <form method="GET" action="">
            <div class="input-group">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Pesquisar"
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
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Imagem</th>
                    <th style="min-width:200px;">Ações</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['titulo']) ?></td>
                        <td>
                            <textarea class="form-control" readonly><?= $row['subtitulo'] ?></textarea>
                        </td>
                        <td>
                            <?php if (!empty($row['imagem'])): ?>
                                <img src="<?= htmlspecialchars($row['imagem']) ?>"
                                     alt="Imagem"
                                     class="fotografia">
                            <?php else: ?>
                                <span>Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>"
                               class="w-100 mb-1 btn btn-primary">
                                Alterar
                            </a>
                            <a href="delete.php?id=<?= $row['id'] ?>"
                               class="w-100 mb-1 btn btn-danger">
                                Apagar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>"
                   class="btn btn-secondary">Anterior</a>
            <?php endif; ?>

            <?php if ($totalRows > $perPage): ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"
                       class="btn btn-light <?= ($i == $page) ? 'active' : '' ?>">
                       <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>"
                   class="btn btn-secondary">Próximo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
