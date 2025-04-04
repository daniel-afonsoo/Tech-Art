<?php
require "../verifica.php";
require "../config/basedados.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id     = $_POST["id"];

    // Remove todas as tags HTML para salvar só texto puro
    $texto_pt = strip_tags($_POST["texto_pt"]);
    $texto_en = strip_tags($_POST["texto_en"]);

    $titulo_pt = strip_tags($_POST["titulo_pt"]);
    $titulo_en = strip_tags($_POST["titulo_en"]);


    // Monta a query de inserção
    $sql = "INSERT INTO missao (id,texto_pt, texto_en,titulo_pt,titulo_en) 
            VALUES (?,?,?,?,?)";

    // Prepara a instrução
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issss', $id, $texto_pt, $texto_en, $titulo_pt, $titulo_en);

    // Executa
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, redireciona
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
        .ck-editor__editable {
            min-height: 200px;
        }
        .halfCol {
            max-width: 50%;
            display: inline-block;
            vertical-align: top;
            height: fit-content;
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
                    <label>ID</label>
                    <input type="text"
                           class="form-control"
                           name="id"
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
                 <!-- Texto (Português) e Texto (Inglês) -->
                 <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Titulo (Português)</label>
                            <textarea class="form-control ck_replace"
                                      name="titulo_pt"
                                      rows="5"></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Titulo (Inglês)</label>
                            <textarea class="form-control ck_replace"
                                      name="titulo_en"
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
