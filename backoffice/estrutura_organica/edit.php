<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para editar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Caminho onde a imagem será salva
$filesDir = "../assets/estrutura_organica/";

// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST["id"];
    $titulo     = $_POST["titulo"];
    $subtitulo  = $_POST["subtitulo"];
    $cargo      = $_POST["cargo"];
    $nome       = $_POST["nome"];
    $oldFoto    = $_POST["old_fotografia"] ?? '';

    // Limpa tags HTML indesejadas
    $titulo     = strip_tags($titulo);
    $subtitulo  = strip_tags($subtitulo);
    $cargo      = strip_tags($cargo);
    $nome       = strip_tags($nome);

    // Verifica se foi carregada uma nova imagem
    if (isset($_FILES["fotografia"]) && $_FILES["fotografia"]["size"] > 0) {
        $newFileName = uniqid() . '_' . $_FILES["fotografia"]["name"];
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $filesDir . $newFileName);
        $fotografia = $newFileName;

    } else {
        // Mantém a foto antiga
        $fotografia = $oldFoto;
    }

    // Atualiza os dados na base de dados
    $sql = "UPDATE estrutura_organica
               SET titulo     = ?,
                   subtitulo  = ?,
                   cargo      = ?,
                   nome       = ?,
                   fotografia = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt, 'sssssi', 
        $titulo, 
        $subtitulo, 
        $cargo, 
        $nome, 
        $fotografia,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao atualizar registro: " . mysqli_error($conn);
    }
} else {
    // Carregar os dados para exibir no formulário
    $id = $_GET["id"];
    $sql = "SELECT titulo, subtitulo, cargo, nome, fotografia 
              FROM estrutura_organica
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $titulo     = $row["titulo"];
    $subtitulo  = $row["subtitulo"];
    $cargo      = $row["cargo"];
    $nome       = $row["nome"];
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
    <title>Editar Registo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
        <h5 class="card-header text-center">Editar Registo</h5>
        <div class="card-body">

            <form action="edit.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="old_fotografia" value="<?= htmlspecialchars($fotografia) ?>">

                <!-- Título -->
                <div class="form-group">
                    <label>Título</label>
                    <input 
                        type="text" 
                        required 
                        maxlength="255" 
                        name="titulo" 
                        class="form-control" 
                        value="<?= htmlspecialchars($titulo) ?>">
                </div>

                <!-- Subtítulo -->
                <div class="form-group">
                    <label>Subtítulo</label>
                    <input 
                        type="text" 
                        maxlength="255" 
                        name="subtitulo" 
                        class="form-control" 
                        value="<?= htmlspecialchars($subtitulo) ?>">
                </div>

                <!-- Cargo -->
                <div class="form-group">
                    <label>Cargo</label>
                    <input 
                        type="text" 
                        maxlength="255" 
                        name="cargo" 
                        class="form-control" 
                        value="<?= htmlspecialchars($cargo) ?>">
                </div>

                <!-- Nome -->
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

                <!-- Fotografia -->
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

                <!-- Botões -->
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
</body>
</html>

