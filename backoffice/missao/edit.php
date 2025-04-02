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
    $chave     = $_POST["chave"];
    // Remove todas as tags HTML para salvar texto puro
    $texto_pt  = strip_tags($_POST["texto_pt"]);
    $texto_en  = strip_tags($_POST["texto_en"]);
    

    // Atualiza os dados na base de dados
    $sql = "UPDATE missao
               SET chave = ?, 
                   texto_pt = ?, 
                   texto_en = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', 
                           $chave, 
                           $texto_pt, 
                           $texto_en,  
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
    $sql = "SELECT chave, texto_pt, texto_en FROM missao WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $chave       = $row["chave"];
    $texto_pt   = $row["texto_pt"];
    $texto_en   = $row["texto_en"]; 
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
                    <label>Chave</label>
                    <input type="text" 
                           required 
                           maxlength="255" 
                           name="chave" 
                           class="form-control" 
                           value="<?= htmlspecialchars($chave) ?>">
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
