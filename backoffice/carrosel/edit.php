<?php
require "../verifica.php";
require "../config/basedados.php";



$mainDir = "../assets/carousel/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Dados do formulário
    $id         = $_POST["id"];
    $titulo     = $_POST["titulo"]     ?? '';
    $subtitulo  = $_POST["subtitulo"]  ?? '';
    $oldImagem  = $_POST["old_imagem"] ?? '';

    // Verifica se foi carregada nova imagem
    $nomeImagem = $oldImagem; 
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
        
        if (!empty($oldImagem) && file_exists($oldImagem)) {
            unlink($oldImagem);
        }

        $tempName     = $_FILES["imagem"]["tmp_name"];
        $originalName = uniqid() . '_' . $_FILES["imagem"]["name"];
        $nomeImagem   = $mainDir . $originalName;
        move_uploaded_file($tempName, $nomeImagem);
    }

    // Atualiza o registo
    $sql = "UPDATE carousel
               SET titulo    = ?,
                   subtitulo = ?,
                   imagem    = ?
             WHERE id        = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $titulo, $subtitulo, $nomeImagem, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }

} else {
    // Se for GET, buscar dados do slide para exibir
    $id = $_GET["id"] ?? 0;

    $sql = "SELECT * FROM carousel WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $titulo    = $row["titulo"];
        $subtitulo = $row["subtitulo"];
        $imagem    = $row["imagem"];
    } else {
        echo "Slide não encontrado!";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Slide</title>
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
        <h2>Editar Slide</h2>
    </div>

    <form action="edit.php" method="POST" enctype="multipart/form-data" class="mt-4">
        <!-- ID e imagem antiga (hidden) -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <input type="hidden" name="old_imagem" value="<?= htmlspecialchars($imagem) ?>">

        <!-- Título -->
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text"
                   name="titulo"
                   id="titulo"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($titulo) ?>">
        </div>

        <!-- Subtítulo -->
        <div class="form-group">
            <label for="subtitulo">Subtítulo</label>
            <textarea name="subtitulo" id="subtitulo" class="form-control" rows="3"><?= htmlspecialchars($subtitulo) ?></textarea>
        </div>

        <!-- Imagem -->
        <div class="form-group">
            <label>Imagem Atual:</label><br>
            <?php if (!empty($imagem) && file_exists($imagem)): ?>
                <img src="<?= htmlspecialchars($imagem) ?>"
                     alt="Slide"
                     class="fotografia mb-3">
            <?php else: ?>
                <p>Sem imagem</p>
            <?php endif; ?>
            <br>
            <label for="imagem">Alterar Imagem</label>
            <input type="file" name="imagem" id="imagem" class="form-control-file" accept="image/*">
        </div>

        <!-- Botões -->
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
