<?php
require "../verifica.php";
require "../config/basedados.php";



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // POST => Apagar
    $id     = $_POST["id"];
    $imagem = $_POST["imagem"] ?? '';

    // Apaga o registo
    $sql  = "DELETE FROM carousel WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        // Apagar a imagem do servidor
        if (!empty($imagem) && file_exists($imagem)) {
            unlink($imagem);
        }
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao apagar: " . mysqli_error($conn);
    }

} else {
   
    $id = $_GET["id"] ?? 0;

    $sql  = "SELECT titulo, subtitulo, imagem FROM carousel WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $titulo    = $row["titulo"];
        $subtitulo = $row["subtitulo"];
        $imagem    = $row["imagem"];
    } else {
        echo "Slide não encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Apagar Slide</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .table-title {
            background: rgb(71,118,165);
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .btn-primary { background: blue; border: none; }
        .btn-danger  { background: red;  border: none; }
        .btn-success { background: green;border: none; }
        .btn-warning { background: orange;border: none; }
        img.fotografia {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="table-title">
        <h2>Apagar Slide</h2>
    </div>

    <div class="alert alert-warning mt-4">
        Tens a certeza que queres apagar este slide?
    </div>

    <form action="delete.php" method="POST">
        <!-- Inputs hidden -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <input type="hidden" name="imagem" value="<?= htmlspecialchars($imagem) ?>">

        <!-- Título -->
        <div class="form-group">
            <label>Título</label>
            <input type="text"
                   class="form-control"
                   value="<?= htmlspecialchars($titulo) ?>"
                   readonly>
        </div>

        <!-- Subtítulo -->
        <div class="form-group">
            <label>Subtítulo</label>
            <textarea class="form-control" rows="3" readonly><?= htmlspecialchars($subtitulo) ?></textarea>
        </div>

        <!-- Imagem -->
        <div class="form-group">
            <label>Imagem</label><br>
            <?php if (!empty($imagem) && file_exists($imagem)): ?>
                <img src="<?= htmlspecialchars($imagem) ?>" alt="Slide" class="fotografia mb-3">
            <?php else: ?>
                <p>Sem imagem</p>
            <?php endif; ?>
        </div>

        <!-- Botões -->
        <button type="submit" class="btn btn-danger">Apagar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
