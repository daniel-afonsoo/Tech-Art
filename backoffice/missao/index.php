<?php
require "../verifica.php";
require "../config/basedados.php";

$search = $_GET['search'] ?? '';
$perPage = 10;
$searchName = '%' . $search . '%';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$sql = "SELECT id, nome, texto_pt, texto_en, fotografia FROM textos_site WHERE nome LIKE '$searchName' LIMIT $start, $perPage";
$result = mysqli_query($conn, $sql);

$totalSql = "SELECT COUNT(*) FROM textos_site WHERE nome LIKE '$searchName'";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_row($totalResult)[0];
$totalPages = ceil($totalRows / $perPage);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missão e Objetivos</title>
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
    <div class="table-title d-flex justify-content-between align-items-center">
        <h2>Missão e Objetivos</h2>
        <a href="add.php" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Novo</a>
    </div>

    <div class="mb-3">
        <form method="GET" action="">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar" value="<?= $search ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Texto (PT)</th>
                    <th>Texto (EN)</th>
                    <th>Fotografia</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['nome'] ?></td>
                        <td><textarea class="form-control" readonly><?= $row['texto_pt'] ?></textarea></td>
                        <td><textarea class="form-control" readonly><?= $row['texto_en'] ?></textarea></td>
                        <td>
                            <?php if ($row['fotografia']): ?>
                                <img src="<?= $row['fotografia'] ?>" alt="Fotografia" class="fotografia">
                            <?php else: ?>
                                <span>Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td style="min-width:200px;">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="w-100 mb-1 btn btn-primary">Alterar</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="w-100 mb-1 btn btn-danger">Apagar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?= $search ?>&page=<?= $page - 1 ?>" class="btn btn-secondary">Anterior</a>
            <?php endif; ?>

            <?php if ($totalRows >= 5): ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?search=<?= $search ?>&page=<?= $i ?>" class="btn btn-light"><?= $i ?></a>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?search=<?= $search ?>&page=<?= $page + 1 ?>" class="btn btn-secondary">Próximo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function searchTable() {
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("tableBody");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }
</script>

</body>
</html>
