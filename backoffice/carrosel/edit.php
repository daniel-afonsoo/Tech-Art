<?php
require "../verifica.php";
require "../config/basedados.php";

// Diretoria onde as imagens são salvas
$mainDir = "../assets/carousel/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Dados do formulário
    $id            = $_POST["id"];
    $chave         = $_POST["chave"]         ?? '';
    $titulo_pt     = $_POST["titulo_pt"]     ?? '';
    $subtitulo_pt  = $_POST["subtitulo_pt"]  ?? '';
    $titulo_en     = $_POST["titulo_en"]     ?? '';
    $subtitulo_en  = $_POST["subtitulo_en"]  ?? '';
    $oldImagem     = $_POST["old_imagem"]    ?? '';

    // Verifica se foi carregada nova imagem
    $nomeImagem = $oldImagem; // Se não fizer upload novo, mantemos a antiga
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
        // Apaga a imagem antiga, se existir
        if (!empty($oldImagem) && file_exists($oldImagem)) {
            unlink($oldImagem);
        }

        $tempName     = $_FILES["imagem"]["tmp_name"];
        $originalName = uniqid() . '_' . $_FILES["imagem"]["name"];
        $nomeImagem = $mainDir . $originalName;
      
        move_uploaded_file($tempName, $nomeImagem);
    }

    // Atualiza o registro 
    $sql = "UPDATE carousel
               SET chave         = ?,
                   titulo_pt     = ?,
                   subtitulo_pt  = ?,
                   titulo_en     = ?,
                   subtitulo_en  = ?,
                   imagem        = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssi',
        $chave,
        $titulo_pt,
        $subtitulo_pt,
        $titulo_en,
        $subtitulo_en,
        $nomeImagem,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }

} else {
    // Se for GET, buscar dados do slide para exibir
    $id = $_GET["id"] ?? 0;

    $sql = "SELECT id, chave, titulo_pt, subtitulo_pt, titulo_en, subtitulo_en, imagem
            FROM carousel
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $chave         = $row["chave"];
        $titulo_pt     = $row["titulo_pt"];
        $subtitulo_pt  = $row["subtitulo_pt"];
        $titulo_en     = $row["titulo_en"];
        $subtitulo_en  = $row["subtitulo_en"];
        $imagem        = $row["imagem"];
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
        .btn-success { background: green; border: none; }
        .btn-warning { background: orange; border: none; }
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

        <!-- Chave -->
        <div class="form-group">
            <label for="chave">Chave</label>
            <input type="text"
                   name="chave"
                   id="chave"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($chave) ?>">
        </div>

        <!-- Título (PT) -->
        <div class="form-group">
            <label for="titulo_pt">Título (PT)</label>
            <input type="text"
                   name="titulo_pt"
                   id="titulo_pt"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($titulo_pt) ?>">
        </div>

        <!-- Subtítulo (PT) -->
        <div class="form-group">
            <label for="subtitulo_pt">Subtítulo (PT)</label>
            <textarea name="subtitulo_pt" id="subtitulo_pt" class="form-control" rows="3"><?= htmlspecialchars($subtitulo_pt) ?></textarea>
        </div>

        <!-- Título (EN) -->
        <div class="form-group">
            <label for="titulo_en">Título (EN)</label>
            <input type="text"
                   name="titulo_en"
                   id="titulo_en"
                   class="form-control"
                   value="<?= htmlspecialchars($titulo_en) ?>">
        </div>

        <!-- Subtítulo (EN) -->
        <div class="form-group">
            <label for="subtitulo_en">Subtítulo (EN)</label>
            <textarea name="subtitulo_en" id="subtitulo_en" class="form-control" rows="3"><?= htmlspecialchars($subtitulo_en) ?></textarea>
        </div>

        <!-- Imagem -->
        <div class="form-group">
            <label>Imagem Atual:</label><br>
            <?php
            $imgPath = $imagem;
            if (!empty($imagem) && file_exists($imgPath)): ?>
                <img src="<?= htmlspecialchars($imgPath) ?>"
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
