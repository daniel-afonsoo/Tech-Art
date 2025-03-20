<?php
require "../verifica.php";
require "../config/basedados.php";

//  Caminho no servidor onde as imagens serão salvas
$mainDir = "../assets/eixos_investigacao/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome     = $_POST["nome"];
    
    // Remove todas as tags HTML para guardar só texto puro
    $texto_pt = strip_tags($_POST["texto_pt"]);

    // Verifica se foi enviado algum arquivo de imagem
    if (
        isset($_FILES["fotografia"]) &&
        $_FILES["fotografia"]["error"] === UPLOAD_ERR_OK &&
        $_FILES["fotografia"]["size"] > 0
    ) {
        // Gera um nome único para o arquivo (ex.: 6412abc_arquivo.jpg)
        $target_file = uniqid() . '_' . $_FILES["fotografia"]["name"];

        // Move o arquivo para a diretoria definida
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $mainDir . $target_file);
    } else {
        // Caso nenhum arquivo tenha sido enviado, deixa o campo vazio
        $target_file = "";
    }

    // Montar a query de inserção
    $sql = "INSERT INTO eixos_investigacao (nome, texto_pt, fotografia) 
            VALUES (?,?,?)";

    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $nome, $texto_pt, $target_file);

   
    if (mysqli_stmt_execute($stmt)) {
        
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao inserir texto: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Texto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

    
    <script src="../ckeditor5/build/ckeditor.js"></script>

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
        .ck-editor__editable {
            min-height: 200px;
        }
        .img-preview {
            display: none;
            width: 100px;
            height: 100px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container-xl mt-5">
    <div class="card">
        <h5 class="card-header text-center">Adicionar Texto</h5>
        <div class="card-body">
           
            <form role="form" data-toggle="validator" action="add.php" method="post" enctype="multipart/form-data">
                
                
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text"
                           class="form-control"
                           name="nome"
                           required
                           data-error="Por favor adicione um nome"
                           maxlength="200">
                    <div class="help-block with-errors"></div>
                </div>

                
                <div class="form-group">
                    <label>Texto (Português)</label>
                    <textarea class="form-control ck_replace"
                              name="texto_pt"
                              rows="5"></textarea>
                    <div class="help-block with-errors"></div>
                </div>

                
                <div class="form-group">
                    <label>Fotografia</label>
                    <input type="file"
                           accept="image/*"
                           onchange="previewImg(this);"
                           class="form-control"
                           name="fotografia">
                </div>
                
                
                <img id="preview" class="img-preview" alt="Preview da imagem" />

               
                <div class="form-group mt-4">
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
