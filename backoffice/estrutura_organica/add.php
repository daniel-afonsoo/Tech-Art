<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para criar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}

// Diretoria aonde as imagens serão salvas
$mainDir = "../assets/estrutura_organica/";

// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo    = $_POST["titulo"];
    $subtitulo = $_POST["subtitulo"];
    $cargo     = $_POST["cargo"];
    $nome      = $_POST["nome"];

    // Tratamento para evitar tags HTML indesejadas
    $titulo    = strip_tags($titulo);
    $subtitulo = strip_tags($subtitulo);
    $cargo     = strip_tags($cargo);
    $nome      = strip_tags($nome);

    // Verifica se foi enviado um arquivo de imagem
    if (isset($_FILES["fotografia"]) && $_FILES["fotografia"]["error"] === UPLOAD_ERR_OK) {
        // Gera um nome único para o arquivo (ex.: 6412abc_arquivo.jpg)
        $target_file = uniqid() . '_' . $_FILES["fotografia"]["name"];

        // Move o arquivo para o diretoria definida
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $mainDir . $target_file);
    } else {
        // Caso nenhum arquivo tenha sido enviado, deixa o campo vazio
        $target_file = "";
    }

    // Monta a query de inserção
    $sql = "INSERT INTO estrutura_organica (titulo, subtitulo, cargo, nome, fotografia) 
            VALUES (?,?,?,?,?)";

    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', 
        $titulo, 
        $subtitulo, 
        $cargo, 
        $nome, 
        $target_file
    );

    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao inserir registro: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Registo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript">
        function previewImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').setAttribute('src', e.target.result);
                    document.getElementById('preview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                document.getElementById('preview').setAttribute('src', '');
                document.getElementById('preview').style.display = 'none';
            }
        }
    </script>

    <style>
        .container {
            max-width: 550px;
        }
        .img-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <h5 class="card-header text-center">Adicionar Registo</h5>
        <div class="card-body">
            <form action="add.php" method="post" enctype="multipart/form-data">
                
                <!-- Título -->
                <div class="form-group">
                    <label>Título</label>
                    <input type="text"
                           class="form-control"
                           name="titulo"
                           required
                           maxlength="200">
                </div>

                <!-- Subtítulo -->
                <div class="form-group">
                    <label>Subtítulo</label>
                    <input type="text"
                           class="form-control"
                           name="subtitulo"
                           maxlength="200">
                </div>

                <!-- Cargo -->
                <div class="form-group">
                    <label>Cargo</label>
                    <input type="text"
                           class="form-control"
                           name="cargo"
                           maxlength="200">
                </div>

                <!-- Nome -->
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text"
                           class="form-control"
                           name="nome"
                           required
                           maxlength="200">
                </div>

                <!-- Fotografia-->
                <div class="form-group">
                    <label>Fotografia</label>
                    <input type="file"
                           accept="image/*"
                           onchange="previewImg(this);"
                           class="form-control"
                           name="fotografia">
                    <img id="preview" class="img-preview" style="display: none;" />
                </div>

                <!-- Botões -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-block">Criar</button>
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
