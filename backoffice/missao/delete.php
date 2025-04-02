<?php
require "../verifica.php";
require "../config/basedados.php";

if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST["id"];
    

    
    $sql  = "DELETE FROM missao WHERE id = ?";
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
    $sql  = "SELECT id, texto_pt, texto_en,titulo_en,titulo_pt FROM missao WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $id      = $row["id"];
        $texto_pt   = $row["texto_pt"];
        $texto_en   = $row["texto_en"];
        $titulo_pt   = $row["titulo_pt"];
        $titulo_en   = $row["titulo_en"];
    } else {
        echo "Registo não encontrado.";
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
    <title>Remover Texto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

    <style>
        .container {
            max-width: 550px;
        }
        .has-error label,
        .has-error input,
        .has-error textarea {
            color: red;
            border-color: red;
        }
        .list-unstyled li {
            font-size: 13px;
            padding: 4px 0 0;
            color: red;
        }
    </style>
</head>
<body>

<div class="container-xl mt-5">
    <div class="card">
        <h5 class="card-header text-center">Remover Texto</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="delete.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="fotografia" value="<?= htmlspecialchars($fotografia) ?>">

                <!-- Chave -->
                <div class="form-group">
                    <label>ID</label>
                    <input type="text" readonly name="id" class="form-control" 
                           value="<?= htmlspecialchars($id) ?>">
                    <div class="help-block with-errors"></div>
                </div>

                <!-- Texto (Português) e Texto (Inglês) -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Texto (Português)</label>
                            <textarea readonly class="form-control" name="texto_pt" rows="4"><?= htmlspecialchars($texto_pt) ?></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Texto (Inglês)</label>
                            <textarea readonly class="form-control" name="texto_en" rows="4"><?= htmlspecialchars($texto_en) ?></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                 <!-- Texto (Português) e Texto (Inglês) -->
                 <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo(Português)</label>
                            <textarea readonly class="form-control" name="titulo_pt" rows="4"><?= htmlspecialchars($titulo_pt) ?></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo(Inglês)</label>
                            <textarea readonly class="form-control" name="titulo_en" rows="4"><?= htmlspecialchars($titulo_en) ?></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Confirmar</button>
                </div>
                <div class="form-group">
                    <button type="button" onclick="window.location.href = 'index.php'" 
                            class="btn btn-danger btn-block">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
