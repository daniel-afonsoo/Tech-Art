<?php
require "../verifica.php";
require "../config/basedados.php";
require "bloqueador.php";

//$mainDir = "../assets/noticias/";
$mainDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\noticias\\";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $conteudo = $_POST["conteudo"];
    $titulo_en = $_POST["titulo_en"];
    $conteudo_en = $_POST["conteudo_en"];
    $data = $_POST["data"];



    $imagem_exists = isset($_FILES["imagem"]) && $_FILES["imagem"]["size"] != 0;


    $sql = "UPDATE noticias SET ultimo_editor = " . $_SESSION["adminid"] . ", timestamp_editado = CURRENT_TIMESTAMP, " .
        "titulo = ?, conteudo = ?, titulo_en = ?, conteudo_en = ?, data = ?";
    $params = [$titulo, $conteudo, $titulo_en, $conteudo_en, $data];

    // Check if the 'imagem' file exists and update the SQL query and parameters accordingly
    if ($imagem_exists) {
        $imagem = uniqid() . '_' .  $_FILES["imagem"]["name"];
        $sql .= ", imagem = ? ";
        $params[] = $imagem;
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $mainDir . $imagem);
    }


    $sql .= " WHERE id = ?";
    $params[] = $id;
    $stmt = mysqli_prepare($conn, $sql);
    $param_types = str_repeat('s', count($params) - 1) . 'i';

    mysqli_stmt_bind_param($stmt, $param_types, ...$params);


    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . $sql . mysqli_error($conn);
    }
} else {

    //Se o request não for um post, selecionar os dados da base de dados para mostrar 
    $sql = "SELECT titulo, titulo_en, conteudo, conteudo_en, imagem, data FROM noticias WHERE id = ?";
    $id = $_GET["id"];
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $titulo = $row["titulo"];
    $conteudo = $row["conteudo"];
    $data = $row["data"];
    $imagem = $row["imagem"];
    $titulo_en = $row["titulo_en"];
    $conteudo_en = $row["conteudo_en"];
}


?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</link>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
<script type="text/javascript">
    function previewImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#preview').attr('src', '<?= $mainDir . $imagem ?>');
        }
    }
</script>
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

<div class="container-xl mt-5">
    <div class="card">
        <h5 class="card-header text-center">Editar Notícia</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="edit.php" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value=<?php echo $id; ?>>
                <div class="form-group">
                    <label>Data da notícia</label>
                    <input type="date" required class="form-control" id="inputDate" name="data" value="<?php echo $data ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" minlength="1" required maxlength="100" name="titulo" class="form-control" data-error="Por favor adicione um titulo válido" id="inputTitle" placeholder="Titulo" value="<?php echo $titulo; ?>">
                            <!-- Error -->
                            <div class=" help-block with-errors">
                            </div>
                        </div>
                    </div>


                    <div class="col">
                        <div class="form-group">
                            <label>Titulo (Inglês)</label>
                            <input type="text" maxlength="100" name="titulo_en" class="form-control" placeholder="Titulo (Inglês)" value="<?php echo $titulo_en; ?>">
                            <!-- Error -->
                            <div class=" help-block with-errors">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Conteúdo da notícia</label>
                            <textarea class="form-control ck_replace" cols="30" rows="5" data-error="Por favor adicione o conteudo da noticia" id="inputContent" name="conteudo"><?php echo $conteudo; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>

                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Conteúdo (Inglês)</label>
                            <textarea class="form-control ck_replace" cols="30" rows="5" id="inputContentEn" name="conteudo_en"><?php echo $conteudo_en; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label>Imagem</label>
                    <input type="file" accept="image/*" onchange="previewImg(this);" data-error="Por favor adicione uma imagem" class="form-control" id="inputImage" name="imagem">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>
                <img id="preview" src="<?php echo $mainDir . $imagem; ?>" width='100px' height='100px' class="mb-3" />



                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Gravar</button>
                </div>

                <div class="form-group">
                    <button type="button" onclick="window.location.href = 'index.php'" class="btn btn-danger btn-block">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Criar o CKEditor 5-->
<script src="../ckeditor5/build/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        $('.ck_replace').each(function() {
            ClassicEditor.create(this, {
                licenseKey: '',
                simpleUpload: {
                    uploadUrl: '../ckeditor5/upload_image.php'
                }
            }).then(editor => {
                window.editor = editor;
            });
        });
    });

    $('#inputDate').on("change", function(e) {
        var inputDate = $(this).val();
        console.log("TESTING")
        // Check if the input value is a valid date
        if (!isValidDate(inputDate)) {
            console.log("NOT VALID")

            e.currentTarget.setCustomValidity('Por favor adicione uma data válida');
        } else {

            e.currentTarget.setCustomValidity('');
        }
    });

    function isValidDate(dateString) {
        var dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!dateRegex.test(dateString)) {
            return false;
        }
        var date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return false;
        }
        return true;
    }
</script>

<?php
mysqli_close($conn);
?>