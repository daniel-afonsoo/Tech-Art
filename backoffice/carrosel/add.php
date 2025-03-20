<?php
require "../verifica.php";
require "../config/basedados.php";



// Diretoria onde as imagens serão guardadas 
$mainDir = "../assets/carousel/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo    = $_POST["titulo"]    ?? '';
    $subtitulo = $_POST["subtitulo"] ?? '';

    // Prepara a variável que vai para a Base de Dados
    $nomeImagem = "";

    // Verifica se há upload de imagem
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
        $tempName     = $_FILES["imagem"]["tmp_name"];
        $originalName = uniqid() . '_' . $_FILES["imagem"]["name"];

        
        $nomeImagem = $mainDir . $originalName;

        // Move o ficheiro para a pasta
        move_uploaded_file($tempName, $nomeImagem);
    }

    // Insere na tabela 'carousel'
    $sql = "INSERT INTO carousel (titulo, subtitulo, imagem) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $titulo, $subtitulo, $nomeImagem);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao inserir: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Slide ao Carrossel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .table-title {
            background: rgb(71, 118, 165);
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .btn-primary { background: blue; border: none; }
        .btn-danger  { background: red;  border: none; }
        .btn-success { background: green;border: none; }
        .btn-warning { background: orange;border: none; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="table-title">
        <h2>Adicionar Novo Slide</h2>
    </div>

    <form action="add.php" method="POST" enctype="multipart/form-data" class="mt-4">
        <!-- Título -->
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <!-- Subtítulo -->
        <div class="form-group">
            <label for="subtitulo">Subtítulo</label>
            <textarea name="subtitulo" id="subtitulo" class="form-control" rows="3"></textarea>
        </div>

        <!-- Imagem -->
        <div class="form-group">
            <label for="imagem">Imagem</label>
            <input type="file" name="imagem" id="imagem" class="form-control-file" accept="image/*">
        </div>

        <!-- Botões -->
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
