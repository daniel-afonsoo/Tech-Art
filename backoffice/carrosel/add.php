<?php
require "../verifica.php";
require "../config/basedados.php";






    

// Verifica se o utilizador tem permissão
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Diretoria onde as imagens serão guardadas
$mainDir = "../assets/carousel/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupera os campos do formulário
    $chave        = isset($_POST["chave"])        ? $_POST["chave"]        : '';
    $titulo_pt    = isset($_POST["titulo_pt"])    ? $_POST["titulo_pt"]    : '';
    $subtitulo_pt = isset($_POST["subtitulo_pt"]) ? $_POST["subtitulo_pt"] : '';
    $titulo_en    = isset($_POST["titulo_en"])    ? $_POST["titulo_en"]    : '';
    $subtitulo_en = isset($_POST["subtitulo_en"]) ? $_POST["subtitulo_en"] : '';

    // Trata o upload da imagem (caso seja enviado um arquivo)
    $imagem = "";
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
        // Gera um nome único para evitar colisão de nomes
        $uniqueName = uniqid() . "_" . $_FILES["imagem"]["name"];
        // Move o arquivo para a pasta desejada
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $mainDir . $uniqueName);
        
        $imagem = $uniqueName;
       
    }

    // Monta a query de inserção (6 colunas = 6 placeholders)
    $sql = "INSERT INTO carrosel 
                (id, titulo_pt, subtitulo_pt, titulo_en, subtitulo_en, imagem) 
            VALUES (?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss',
        $id,
        $titulo_pt,
        $subtitulo_pt,
        $titulo_en,
        $subtitulo_en,
        $imagem
    );


    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao inserir: " . mysqli_error($conn);
    }
}



// Fechar conexão
mysqli_close($conn);

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
        .btn-success { background: green; border: none; }
        .btn-warning { background: orange; border: none; }
       
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="table-title">
        <h2>Adicionar Novo Slide</h2>
    </div>

    <form action="add.php" method="POST" enctype="multipart/form-data" class="mt-4">
        <!-- Chave -->
        <div class="form-group">
            <label for="chave">ID</label>
            <input type="text" name="id" id="id" class="form-control" required>
        </div>

        <!-- Título (PT) -->
        <div class="form-group">
            <label for="titulo_pt">Título (PT)</label>
            <input type="text" name="titulo_pt" id="titulo_pt" class="form-control" required>
        </div>

        <!-- Subtítulo (PT) -->
        <div class="form-group">
            <label for="subtitulo_pt">Subtítulo (PT)</label>
            <textarea name="subtitulo_pt" id="subtitulo_pt" class="form-control" rows="3"></textarea>
        </div>

        <!-- Título (EN) -->
        <div class="form-group">
            <label for="titulo_en">Título (EN)</label>
            <input type="text" name="titulo_en" id="titulo_en" class="form-control">
        </div>

        <!-- Subtítulo (EN) -->
        <div class="form-group">
            <label for="subtitulo_en">Subtítulo (EN)</label>
            <textarea name="subtitulo_en" id="subtitulo_en" class="form-control" rows="3"></textarea>
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
 