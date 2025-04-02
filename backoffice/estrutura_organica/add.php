<?php
require "../verifica.php";
require "../config/basedados.php";

// Verifica se o utilizador tem permissão para criar
if ($_SESSION["autenticado"] != 'administrador') {
    header("Location: index.php");
    exit;
}


// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $chave     = $_POST["chave"];
    // Remove todas as tags HTML para salvar só texto puro
    $texto_pt = strip_tags($_POST["texto_pt"]);
    $texto_en = strip_tags($_POST["texto_en"]);


    // Monta a query de inserção
    $sql = "INSERT INTO estrutura_organica (chave,texto_pt, texto_en) 
            VALUES (?,?,?)";

    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', 
     $chave, $texto_pt, $texto_en
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
<div class="container-xl mt-5">
    <div class="card">
        <h5 class="card-header text-center">Adicionar Texto</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="add.php" method="post" enctype="multipart/form-data">
                
                <!-- Chave -->
                <div class="form-group">
                    <label>Chave</label>
                    <input type="text"
                           class="form-control"
                           name="chave"
                           required
                           data-error="Por favor adicione uma chave"
                           maxlength="200">
                    <div class="help-block with-errors"></div>
                </div>

                <!-- Texto (Português) e Texto (Inglês) -->
                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Texto (Português)</label>
                            <textarea class="form-control ck_replace"
                                      name="texto_pt"
                                      rows="5"></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Texto (Inglês)</label>
                            <textarea class="form-control ck_replace"
                                      name="texto_en"
                                      rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Botões -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Criar</button>
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

<script>
    // Inicializa o CKEditor 5 em cada textarea que tiver a classe .ck_replace
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.ck_replace').forEach(function(el) {
            ClassicEditor.create(el, {
                licenseKey: '',
                simpleUpload: {
                    uploadUrl: '../ckeditor5/upload_image.php'
                }
            }).then(editor => {
                // Editor inicializado
            }).catch(error => {
                console.error(error);
            });
        });
    });
</script>
</body>
</html>