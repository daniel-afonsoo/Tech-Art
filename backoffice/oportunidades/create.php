<?php
require "../verifica.php";
require "../config/basedados.php";
require "bloqueador.php";

//$mainDir = "../assets/oportunidades/";
$mainDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\oportunidades\\";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!is_dir($mainDir)) {
        mkdir($mainDir, 0777, true);
    }
    $fileName =  uniqid() . '_' . $_FILES["imagem"]["name"];
    if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $mainDir . $fileName)) {
        echo 'Um erro ocorreu ao inserir a imagem ' . $_FILES["imagem"]["tmp_name"] . ' em '.$mainDir . $fileName ;
        exit;
    }

    $sql = "INSERT INTO oportunidades (titulo, titulo_en, conteudo, conteudo_en, imagem, visivel, ultimo_editor) " .
        "VALUES (?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    $titulo = $_POST["titulo"];
    $titulo_en = $_POST["titulo_en"];
    $conteudo = $_POST["conteudo"];
    $conteudo_en = $_POST["conteudo_en"];
    $imagem = $fileName;
    $ultimo_editor = $_SESSION["adminid"];

    $visivel = isset($_POST["visivel"]) ? 1 : 0;
    mysqli_stmt_bind_param($stmt, 'sssssii', $titulo, $titulo_en, $conteudo, $conteudo_en, $imagem, $visivel, $ultimo_editor);
    if (mysqli_stmt_execute($stmt)) {
        if (isset($_FILES['ficheiros']) && !empty($_FILES['ficheiros']['name'][0])) {
            $id = mysqli_insert_id($conn);
            $uploadDir = $mainDir . "ficheiros_$id/pt/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $files = $_FILES['ficheiros'];
            for ($i = 0; $i < count($files['name']); $i++) {
                $fileName = $files['name'][$i];
                $fileTmp = $files['tmp_name'][$i];
                $fileSize = $files['size'][$i];
                $uniqueFileName = $fileName;
                $destination = $uploadDir . $uniqueFileName;
                if (!move_uploaded_file($fileTmp, $destination)) {
                    echo "<script>
                    alert('Um erro ocurreu a inserir os ficheiros'" . $_FILES["file"]["error"][$i] . "');
                    window.location.href = 'edit.php?id=" . $id . "';
                    </script>";
                    exit;
                }
            }
        }
        if (isset($_FILES['ficheiros_en']) && !empty($_FILES['ficheiros_en']['name'][0])) {
            $id = mysqli_insert_id($conn);
            $uploadDirEn = $mainDir . "ficheiros_$id/en/";
            if (!is_dir($uploadDirEn)) {
                mkdir($uploadDirEn, 0777, true);
            }
            $filesEn = $_FILES['ficheiros_en'];
            for ($i = 0; $i < count($filesEn['name']); $i++) {
                $fileNameEn = $filesEn['name'][$i];
                $fileTmpEn = $filesEn['tmp_name'][$i];
                $fileSizeEn = $filesEn['size'][$i];
                $uniqueFileNameEn = $fileNameEn;
                $destinationEn = $uploadDirEn . $uniqueFileNameEn;
                if (!move_uploaded_file($fileTmpEn, $destinationEn)) {
                    echo "<script>
                        alert('Um erro ocurreu a inserir os ficheiros (inglês): " . $_FILES["file"]["error"][$i] . "');
                        window.location.href = 'edit.php?id=" . $id . "';
                        </script>";
                    exit;
                }
            }
        }
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</link>
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
<script type="text/javascript">
    function previewImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
                $('#preview').show();
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#preview').attr('src', '');
            $('#preview').hide();
        }
    }

    function showFileNames(input, fileListId) {
        var fileListDiv = $('#' + fileListId);
        fileListDiv.empty();

        var selectedFiles = input.files;
        if (selectedFiles.length > 0) {
            var fileNames = Array.from(selectedFiles).map(function(file) {
                return file.name;
            });

            fileNames.forEach(function(fileName) {
                var paragraph = '<li class="list-group-item">' + fileName + '<br>' + '</li>'
                fileListDiv.append(paragraph);
            });
        }
    }
</script>
<div class="container-xl mt-5">
    <div class="card">
        <h5 class="card-header text-center">Adicionar Oportunidade</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="create.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="visivel" name="visivel">
                        <label class="form-check-label" for="visivel">
                            Visível
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" minlength="1" required maxlength="100" required name="titulo" class="form-control" data-error="Por favor adicione um titulo válido" id="inputTitle" placeholder="Titulo">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo (Inglês)</label>
                            <input type="text" maxlength="100" name="titulo_en" class="form-control" data-error="Please enter a valid English title" id="inputTitleEn" placeholder="Titulo (Inglês)">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">

                            <label class="col-form-label">Conteúdo da oportunidade</label>
                            <textarea class="form-control ck_replace" id="inputContent" name="conteudo"></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label class="col-form-label">Conteúdo (Inglês)</label>
                            <textarea class="form-control ck_replace" id="inputContentEn" name="conteudo_en"></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Ficheiros</label>
                            <input type="file" onchange="showFileNames(this, 'fileList')" multiple class="form-control" id="ficheiros" name="ficheiros[]">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                            <ul id="fileList" class="mb-3 list-group" style="font-size: 14px;"></ul>
                        </div>
                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Ficheiros (Inglês)</label>
                            <input type="file" onchange="showFileNames(this, 'fileList_en')" multiple class="form-control" id="ficheiros_en" name="ficheiros_en[]">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                            <ul id="fileList_en" class="mb-3 list-group" style="font-size: 14px;"></ul>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Imagem</label>
                    <input accept="image/*" type="file" onchange="previewImg(this);" required data-error="Por favor adicione uma imagem válida" class="form-control" id="inputImage" name="imagem">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>
                <img id="preview" style="display: none;" width='100px' height='100px' class="mb-3" />


                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Criar</button>
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
    <?php require "../../tecnart/config/configurations.php"; ?>

    function handleFileChange(e) {
        var totalSize = 0;
        var files = e.currentTarget.files;
        var valid = true;
        var filesize = 0;
        for (let i = 0; i < files.length; i++) {
            filesize = ((files[i].size / 1024) / 1024);
            totalSize += filesize;

            if (filesize > <?= MAX_FILE_SIZE ?>) {
                valid = false;
                e.currentTarget.setCustomValidity('O tamanho do ficheiro "' + files[i].name + '" é maior que o máximo de <?= MAX_FILE_SIZE ?>MB');
            } else if (totalSize > <?= MAX_FILES_TOTAL ?>) {
                valid = false;
                e.currentTarget.setCustomValidity('Tamanho total de todos os ficheiros é maior que o máximo de <?= MAX_FILES_TOTAL ?>MB');
            }
        }
        if (valid) {
            e.currentTarget.setCustomValidity('');
        }
    }

    $("#ficheiros").on("change", handleFileChange);
    $("#ficheiros_en").on("change", handleFileChange);
</script>

<?php
mysqli_close($conn);
?>