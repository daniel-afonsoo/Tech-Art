<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para apagar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Caminho onde a imagem está salva
$filesDir = "../assets/estrutura_organica/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST["id"];
    $fotografia = $_POST["fotografia"];
    $sql  = "DELETE FROM estrutura_organica WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        if ($fotografia && file_exists($filesDir . $fotografia)) {
            unlink($filesDir . $fotografia);
        }
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao remover o registro: " . mysqli_error($conn);
    }
} else {
    $id = $_GET["id"];

    $sql  = "SELECT titulo, subtitulo, cargo, nome, fotografia 
               FROM estrutura_organica 
              WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $titulo     = $row["titulo"];
        $subtitulo  = $row["subtitulo"];
        $cargo      = $row["cargo"];
        $nome       = $row["nome"];
        $fotografia = $row["fotografia"];
    } else {
        echo "Registro não encontrado.";
        mysqli_close($conn);
        exit;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Remover Registo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <h5 class="card-header text-center">Remover Registo</h5>
        <div class="card-body">
            <form action="delete.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="fotografia" value="<?= htmlspecialchars($fotografia) ?>">

                <div class="form-group">
                    <label>Título</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($titulo) ?>">
                </div>

                <div class="form-group">
                    <label>Subtítulo</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($subtitulo) ?>">
                </div>

                <div class="form-group">
                    <label>Cargo</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($cargo) ?>">
                </div>

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($nome) ?>">
                </div>

                <!-- Fotografia -->
                <div class="form-group">
                    <label>Fotografia</label><br>
                    <?php if ($fotografia && file_exists($filesDir . $fotografia)): ?>
                        <img src="<?= $filesDir . htmlspecialchars($fotografia) ?>" 
                             alt="Fotografia" 
                             style="max-width: 200px; max-height: 200px;">
                    <?php else: ?>
                        <p>Imagem não encontrada.</p>
                    <?php endif; ?>
                </div>

                <!-- Botões -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Confirmar</button>
                </div>
                <div class="form-group">
                    <button type="button" 
                            onclick="window.location.href='index.php'" 
                            class="btn btn-danger btn-block">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
