<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para editar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}


// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = $_POST["id"];
    // Remove todas as tags HTML para salvar texto puro
    $texto_pt  = strip_tags($_POST["texto_pt"]);
    $texto_en  = strip_tags($_POST["texto_en"]);
    $titulo_pt = strip_tags($_POST["titulo_pt"]);
    $titulo_en = strip_tags($_POST["titulo_en"]);
    

    // Atualiza os dados na base de dados
    $sql = "UPDATE missao
               SET texto_pt = ?, 
                   texto_en = ?,
                   titulo_pt = ?, 
                   titulo_en = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', 
                           $texto_pt, 
                           $texto_en,
                           $titulo_pt,
                           $titulo_en,  
                           $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao atualizar texto: " . mysqli_error($conn);
    }
} else {
    // Carregar os dados do texto para exibir no formulário
    $id = $_GET["id"];
    $sql = "SELECT id, texto_pt, texto_en,titulo_en,titulo_pt FROM missao WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $id     = $row["id"];
    $texto_pt   = $row["texto_pt"];
    $texto_en   = $row["texto_en"];
    $titulo_pt   = $row["titulo_pt"];
    $titulo_en   = $row["titulo_en"]; 
}

// Fechar conexão
if ($conn && $conn instanceof mysqli) {
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Texto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <h5 class="card-header text-center">Editar Texto</h5>
        <div class="card-body">
            <form role="form" action="edit.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <div class="form-group">
                    <label>ID</label>
                    <input type="text" 
                           required 
                           maxlength="255" 
                           name="id" 
                           class="form-control" 
                           value="<?= htmlspecialchars($id) ?>">
                </div>

                <div class="form-group">
                    <label>Texto (Português)</label>
                    <textarea name="texto_pt" class="form-control ck_replace"><?= htmlspecialchars($texto_pt) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Texto (Inglês)</label>
                    <textarea name="texto_en" class="form-control ck_replace"><?= htmlspecialchars($texto_en) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Titulo(Português)</label>
                    <textarea name="titulo_pt" class="form-control ck_replace"><?= htmlspecialchars($titulo_pt) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Titulo (Inglês)</label>
                    <textarea name="titulo_en" class="form-control ck_replace"><?= htmlspecialchars($titulo_en) ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Gravar</button>
                </div>
                <div class="form-group">
                    <button type="button" 
                            onclick="window.location.href = 'index.php'" 
                            class="btn btn-danger btn-block">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Inicializa o CKEditor 5
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.ck_replace').forEach(function(element) {
            ClassicEditor.create(element).catch(error => console.error(error));
        });
    });
</script>
</body>
</html>
