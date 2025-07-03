<?php
ob_start(); // Inicia o buffer de saída
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para editar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST["id"];
    $chave     = $_POST["chave"];
    $texto_pt  = $_POST["texto_pt"];
    $texto_en     = $_POST["texto_en"];
    $titulo_pt  = $_POST["titulo_pt"];
    $titulo_en     = $_POST["titulo_en"];

    // Limpa tags HTML indesejadas
    $texto_en    = strip_tags($texto_en);
    $texto_pt  = strip_tags($texto_pt);
    $titulo_en    = strip_tags($titulo_en);
    $titulo_pt  = strip_tags($titulo_pt);

   
    // Atualiza os dados na base de dados
    $sql = "UPDATE estrutura
               SET texto_pt     = ?,
                   texto_en = ?,
                   titulo_pt  = ?,
                   titulo_en     = ?,
                   chave     = ?
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt, 'sssssi', 
        $texto_pt, 
        $texto_en,
        $titulo_pt,
        $titulo_en, 
        $chave,
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
    $sql = "SELECT chave,texto_pt,texto_en, titulo_pt, titulo_en
              FROM estrutura
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $chave     = $row["chave"];
    $texto_en  = $row["texto_en"];
    $texto_pt     = $row["texto_pt"];
    $titulo_en  = $row["titulo_en"];
    $titulo_pt     = $row["titulo_pt"];
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

                <!-- Chave -->
                <div class="form-group">
                    <label>ID</label>
                    <input 
                        type="text" 
                        required 
                        maxlength="255" 
                        name="chave" 
                        class="form-control" 
                        value="<?= htmlspecialchars($chave) ?>">
                </div>

                

              
               <div class="form-group">
                    <label>Texto(Portugues)</label>
                    <input 
                        type="text" 
                        maxlength="1000" 
                        name="texto_pt" 
                        class="form-control" 
                        value="<?= htmlspecialchars($texto_pt) ?>">
                </div>


              
                <div class="form-group">
                    <label>Texto(Ingles)</label>
                    <input 
                        type="text" 
                        maxlength="1000" 
                        name="texto_en" 
                        class="form-control" 
                        value="<?= htmlspecialchars($texto_en) ?>">
                </div>

                <div class="form-group">
                    <label>Título(Portugues)</label>
                    <input   
                        type="text" 
                        maxlength="255" 
                        name="titulo_pt" 
                        class="form-control" 
                        value="<?= htmlspecialchars($titulo_pt) ?>">
                </div>

                <div class="form-group">
                    <label>Título(Ingles)</label>
                    <input
                        type="text" 
                        maxlength="255" 
                        name="titulo_en" 
                        class="form-control" 
                        value="<?= htmlspecialchars($titulo_en) ?>">
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

