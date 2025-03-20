<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para editar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Caminho onde a imagem será salva
$filesDir = "../assets/eixos_investigacao/";

// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = $_POST["id"];
    $nome      = $_POST["nome"];

    // Remove todas as tags HTML para salvar texto puro
    $texto_pt  = strip_tags($_POST["texto_pt"]);

    // Foto antiga
    $oldFoto   = $_POST["old_fotografia"] ?? '';

    // Verifica se foi carregada uma nova imagem
    if (isset($_FILES["fotografia"]) && $_FILES["fotografia"]["size"] != 0) {
        $newFileName = uniqid() . '_' . $_FILES["fotografia"]["name"];
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $filesDir . $newFileName);
        $fotografia = $newFileName;

        
    } else {
        // Mantém a antiga foto
        $fotografia = $oldFoto;
    }

    // Atualiza os dados na base de dados
    $sql = "UPDATE eixos_investigacao
               SET nome = ?, 
                   texto_pt = ?, 
                   fotografia = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', 
                           $nome, 
                           $texto_pt,  
                           $fotografia,
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
    $sql = "SELECT nome, texto_pt, fotografia FROM eixos_investigacao WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $nome       = $row["nome"];
    $texto_pt   = $row["texto_pt"];
    $fotografia = $row["fotografia"]; 
}

// Fechar a conexão
if ($conn && $conn instanceof mysqli) {
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Texto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <style>
        .img-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            display: block;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <h5 class="card-header text-center">Editar Texto</h5>
        <div class="card-body">

            <form role="form" action="edit.php" method="post" enctype="multipart/form-data">
                
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="old_fotografia" value="<?= htmlspecialchars($fotografia) ?>">

                
                <div class="form-group">
                    <label>Nome</label>
                    <input 
                        type="text" 
                        required 
                        maxlength="255" 
                        name="nome" 
                        class="form-control" 
                        value="<?= htmlspecialchars($nome) ?>">
                </div>

               
                <div class="form-group">
                    <label>Texto (Português)</label>
                    <textarea 
                        name="texto_pt" 
                        class="form-control ck_replace"
                        rows="6"><?= htmlspecialchars($texto_pt) ?></textarea>
                </div>

         
                <div class="form-group">
                    <label>Fotografia</label>
                    <?php if (!empty($fotografia) && file_exists($filesDir . $fotografia)): ?>
                        <img 
                            src="<?= $filesDir . htmlspecialchars($fotografia) ?>" 
                            alt="Fotografia" 
                            class="img-preview"
                        >
                    <?php else: ?>
                        <p class="text-muted">Nenhuma imagem encontrada.</p>
                    <?php endif; ?>

                    <input type="file" name="fotografia" class="form-control">
                    <small class="form-text text-muted">
                        Se quiser alterar a fotografia, carregue uma nova imagem.
                    </small>
                </div>

                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-block mb-2">
                        Gravar
                    </button>
                    <button 
                        type="button"
                        onclick="window.location.href='index.php'"
                        class="btn btn-danger btn-block"
                    >
                        Cancelar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.ck_replace').forEach(function(element) {
            ClassicEditor.create(element).catch(error => console.error(error));
        });
    });
</script>
</body>
</html>
