<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para apagar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST["id"];
    $sql  = "DELETE FROM estrutura_organica WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao remover o registro: " . mysqli_error($conn);
    }
} else {
    $id = $_GET["id"];

    $sql  = "SELECT chave,texto_en,texto_pt
               FROM estrutura_organica 
              WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $chave     = $row["chave"];
        $texto_pt  = $row["texto_pt"];
        $texto_en     = $row["texto_en"];
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
                    <label>Chave</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($chave) ?>">
                </div>

                <div class="form-group">
                    <label>Texto(PT)</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($texto_pt) ?>">
                </div>

                <div class="form-group">
                    <label>Texto(EN)</label>
                    <input type="text" readonly class="form-control" 
                           value="<?= htmlspecialchars($texto_en) ?>">
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
