<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para editar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Caminho onde a imagem será salva
$filesDir = "../assets/missao/";

// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = $_POST["id"];
    $nome      = $_POST["nome"];
    // Remove todas as tags HTML para salvar texto puro
    $texto_pt  = strip_tags($_POST["texto_pt"]);
    $texto_en  = strip_tags($_POST["texto_en"]);
    // Caso esteja vindo o nome antigo da foto, podemos usar:
    $oldFoto   = $_POST["old_fotografia"] ?? '';

    // Verifica se foi carregada uma nova imagem
    if (isset($_FILES["fotografia"]) && $_FILES["fotografia"]["size"] != 0) {
        // Gera nome único e move a imagem
        $newFileName = uniqid() . '_' . $_FILES["fotografia"]["name"];
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $filesDir . $newFileName);
        $fotografia = $newFileName;

        
    } else {
        // Se não foi carregada nova imagem, mantém a antiga
        $fotografia = $oldFoto;
    }

    // Atualiza os dados na base de dados
    $sql = "UPDATE textos_site 
               SET nome = ?, 
                   texto_pt = ?, 
                   texto_en = ?, 
                   fotografia = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', 
                           $nome, 
                           $texto_pt, 
                           $texto_en, 
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
    $sql = "SELECT nome, texto_pt, texto_en, fotografia FROM textos_site WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $nome       = $row["nome"];
    $texto_pt   = $row["texto_pt"];
    $texto_en   = $row["texto_en"];
    $fotografia = $row["fotografia"]; 
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
                <!-- Armazena a foto antiga para poder manter se não fizer upload novo -->
                <input type="hidden" name="old_fotografia" value="<?= htmlspecialchars($fotografia) ?>">

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" 
                           required 
                           maxlength="255" 
                           name="nome" 
                           class="form-control" 
                           value="<?= htmlspecialchars($nome) ?>">
                </div>

                <div class="form-group">
                    <label>Texto (Português)</label>
                    <textarea name="texto_pt" class="form-control ck_replace"><?= htmlspecialchars($texto_pt) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Texto (Inglês)</label>
                    <textarea name="texto_en" class="form-control ck_replace"><?= htmlspecialchars($texto_en) ?></textarea>
                </div>

                <!-- Campo de fotografia -->
                <div class="form-group">
                    <label>Fotografia</label><br>
                    <?php if (!empty($fotografia) && file_exists($filesDir . $fotografia)): ?>
                        <img src="<?= $filesDir . htmlspecialchars($fotografia) ?>" 
                             alt="Fotografia" 
                             style="max-width: 200px; max-height: 200px;">
                    <?php else: ?>
                        <p>Nenhuma imagem ou arquivo não encontrado.</p>
                    <?php endif; ?>
                    <input type="file" name="fotografia" class="form-control mt-2">
                    <small class="form-text text-muted">Se quiser alterar a fotografia, carregue uma nova imagem.</small>
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
