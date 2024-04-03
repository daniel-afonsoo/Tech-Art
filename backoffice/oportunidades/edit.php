<?php
require "../verifica.php";
require "../config/basedados.php";
require "bloqueador.php";

$id = '';
$titulo = '';
$conteudo = '';
$imagem = '';
$visivel = '';
$titulo_en = '';
$conteudo_en = '';

//$mainDir = "../assets/oportunidades/";
$mainDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\data\\techneart\\assets\\oportunidades\\";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $filesDir = $mainDir . "ficheiros_$id/pt/";
    $filesDirEn = $mainDir . "ficheiros_$id/en/";
    $titulo = $_POST["titulo"];
    $conteudo = $_POST["conteudo"];
    $titulo_en = $_POST["titulo_en"];
    $conteudo_en = $_POST["conteudo_en"];
    $visivel = isset($_POST["visivel"]) ? 1 : 0;


    $imagem_exists = isset($_FILES["imagem"]) && $_FILES["imagem"]["size"] != 0;


    $sql = "UPDATE oportunidades SET ultimo_editor = " . $_SESSION["adminid"] . ", timestamp_editado = CURRENT_TIMESTAMP, " .
        "titulo = ?, conteudo = ?, titulo_en = ?, conteudo_en = ?";
    $params = [$titulo, $conteudo, $titulo_en, $conteudo_en];

    // Check if the 'imagem' file exists and update the SQL query and parameters accordingly
    if ($imagem_exists) {
        $imagem = uniqid() . '_' .  $_FILES["imagem"]["name"];
        $sql .= ", imagem = ? ";
        $params[] = $imagem;
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $mainDir . $imagem);
    }

    $sql .= ", visivel = ? WHERE id = ?";
    array_push($params, $visivel, $id);
    $stmt = mysqli_prepare($conn, $sql);
    $param_types = str_repeat('s', count($params) - 2) . 'ii';

    mysqli_stmt_bind_param($stmt, $param_types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        if (!is_dir($filesDir)) {
            mkdir($filesDir, 0777, true);
        }
        if (!is_dir($filesDirEn)) {
            mkdir($filesDirEn, 0777, true);
        }

        $folderFiles = array_diff(scandir($filesDir), array('.', '..'));
        $selectedFiles = isset($_POST['selectedFiles']) ? $_POST['selectedFiles'] : [];

        $folderFilesEn = array_diff(scandir($filesDirEn), array('.', '..'));
        $selectedFilesEn = isset($_POST['selectedFilesEn']) ? $_POST['selectedFilesEn'] : [];

        // Remove unselected files from the folder
        foreach ($folderFiles as $file) {
            if (!in_array($file, $selectedFiles)) {
                unlink($filesDir . '/' . $file);
            }
        }

        // Remove unselected files from the English folder
        foreach ($folderFilesEn as $file) {
            if (!in_array($file, $selectedFilesEn)) {
                unlink($filesDirEn . '/' . $file);
            }
        }

        // Handle the uploaded files
        handleUploadedFiles('ficheiros', $filesDir, $selectedFiles, $id);
        handleUploadedFiles('ficheiros_en', $filesDirEn, $selectedFilesEn, $id);

        header('Location: index.php');
    } else {
        echo "Error: " . $sql . mysqli_error($conn);
    }
} else {
    if (isset($_GET["id"])) {
        // Se o request não for um post, selecionar os dados da base de dados para mostrar 
        $sql = "SELECT titulo, conteudo, titulo_en, conteudo_en, imagem, visivel FROM oportunidades WHERE id = ?";
        $id = $_GET["id"];
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $titulo = $row["titulo"];
        $conteudo = $row["conteudo"];
        $titulo_en = $row["titulo_en"];
        $conteudo_en = $row["conteudo_en"];
        $imagem = $row["imagem"];
        $visivel = $row["visivel"] ? "checked" : "";

        $filesDir = $mainDir . "ficheiros_$id/pt/";
        $filesDirEn = $mainDir . "ficheiros_$id/en/";
        $files = [];
        if (is_dir($filesDir)) {
            $files = scandir($filesDir);
            $files = array_diff($files, array('.', '..'));
        }
        $filesEn = [];
        if (is_dir($filesDirEn)) {
            $filesEn = scandir($filesDirEn);
            $filesEn = array_diff($filesEn, array('.', '..'));
        }
    }
}

function handleUploadedFiles($fieldName, $destinationDir, &$selectedFiles, $id)
{
    if (isset($_FILES[$fieldName]) && !empty($_FILES[$fieldName]['name'][0])) {
        $fileCount = count($_FILES[$fieldName]['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $tmpFilePath = $_FILES[$fieldName]['tmp_name'][$i];
            $newFilePath = $destinationDir . '/' . $_FILES[$fieldName]['name'][$i];

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $selectedFiles[] = $_FILES[$fieldName]['name'][$i];
            } else {
                echo "<script>
                        alert('Um erro ocorreu ao inserir os ficheiros (" . getLanguageName($fieldName) . ") " . $_FILES[$fieldName]['error'][$i] . "');
                        window.location.href = 'edit.php?id=" . $id . "';
                      </script>";
            }
        }
    }
}

function getLanguageName($fieldName)
{
    if ($fieldName === 'ficheiros_en') {
        return 'Inglês';
    } else {
        return '';
    }
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
        <h5 class="card-header text-center">Editar Oportunidade</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="edit.php" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value=<?php echo $id; ?>>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="visivel" name="visivel" <?= $visivel ?>>
                        <label class="form-check-label" for="visivel">
                            Visível
                        </label>
                    </div>
                </div>


                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" minlength="1" required maxlength="100" name="titulo" class="form-control" data-error="Por favor adicione um título válido" id="inputTitle" placeholder="Título" value="<?php echo $titulo; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Titulo (Inglês)</label>
                            <input type="text" maxlength="100" name="titulo_en" class="form-control" id="inputTitleEn" placeholder="Título" value="<?php echo $titulo_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">

                            <label class="col-form-label">Conteúdo da oportunidade</label>
                            <textarea class="form-control ck_replace" data-error="Por favor adicione o conteudo da oportunidade" id="inputContent" name="conteudo"><?php echo $conteudo; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label class="col-form-label">Conteúdo (Inglês)</label>
                            <textarea class="form-control ck_replace" data-error="Por favor adicione o conteudo da oportunidade" id="inputContentEn" name="conteudo_en"><?php echo $conteudo_en; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Adicionar Ficheiros</label>
                            <input type="file" onchange="showFileNames(this, 'fileList')" multiple class="form-control" id="ficheiros" name="ficheiros[]">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                            <ul id="fileList" class="mb-3 list-group" style="font-size: 14px;"></ul>
                        </div>
                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Adicionar Ficheiros (Inglês)</label>
                            <input type="file" onchange="showFileNames(this, 'fileList_en')" multiple class="form-control" id="ficheiros_en" name="ficheiros_en[]">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                            <ul id="fileList_en" class="mb-3 list-group" style="font-size: 14px;"></ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col halfCol">
                        Ficheiros atuais:
                        <p style="font-size: 14px;" class="mb-1">Os ficheiros não selecionados serão eliminados permanentemente</p>
                        <div id="conflictMessage" style="color: red; display:none; font-size: 14px;">AVISO - Existem ficheiros que vão ser substituidos pelos ficheiros adicionados</div> <!-- Conflict Message -->

                        <div id="folderFilesList" class="mb-3 pl-4">
                            <?php
                            // Display the list of files in the folder with checkboxes
                            foreach ($files as $file) {
                                echo '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="selectedFiles[]" value="' . $file . '" checked id="' . $file . '">
                                <label class="form-check-label" for="' . $file . '">
                                    <a href="' . $filesDir . '/' . $file . '" target="_blank">' . $file . '</a>
                                </label>
                            </div>
                            ';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col halfCol">
                        Ficheiros atuais (Inglês):
                        <p style="font-size: 14px;" class="mb-1">Os ficheiros não selecionados serão eliminados permanentemente</p>
                        <div id="conflictMessageEn" style="color: red; display:none; font-size: 14px;">AVISO - Existem ficheiros que vão ser substituidos pelos ficheiros adicionados</div> <!-- Conflict Message -->

                        <div id="folderFilesListEn" class="mb-3 pl-4">
                            <?php
                            // Display the list of files in the folder with checkboxes
                            foreach ($filesEn as $file) {
                                echo '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="selectedFilesEn[]" value="' . $file . '" checked id="' . $file . '">
                                <label class="form-check-label" for="' . $file . '">
                                    <a href="' . $filesDirEn . '/' . $file . '" target="_blank">' . $file . '</a>
                                </label>
                            </div>
                            ';
                            }
                            ?>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label>Imagem</label>
                    <input accept="image/*" type="file" class="form-control" id="inputImage" name="imagem" onchange="previewImg(this);">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>

                </div>
                <img id="preview" src="<?php echo $mainDir . $imagem; ?>" width='100px' height='100px' class="mb-2 mt-3" />


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
    <?php require "../../tecnart/config/configurations.php"; ?>

    function handleFileChange(input, fileListDiv, conflictMessage, checkboxes) {
        var totalSize = 0;
        var files = input.files;
        var valid = true;
        var filesize = 0;
        for (let i = 0; i < files.length; i++) {
            filesize = ((files[i].size / 1024) / 1024);
            totalSize += filesize;

            if (filesize > <?= MAX_FILE_SIZE ?>) {
                valid = false;
                input.setCustomValidity('O tamanho do ficheiro "' + files[i].name + '" é maior que o máximo de <?= MAX_FILE_SIZE ?>MB');
            } else if (totalSize > <?= MAX_FILES_TOTAL ?>) {
                valid = false;
                input.setCustomValidity('Tamanho total de todos os ficheiros é maior que o máximo de <?= MAX_FILES_TOTAL ?>MB');
            }
        }
        if (valid) {
            input.setCustomValidity('');
        }

        var selectedFiles = Array.from(input.files);
        var hasConflicts = false;

        checkboxes.forEach(function(checkbox) {
            var checkboxValue = checkbox.value;
            var matchingFile = selectedFiles.find(function(file) {
                return file.name === checkboxValue;
            });

            if (matchingFile) {
                checkbox.nextElementSibling.querySelector('a').style.color = 'red';
                if (!checkbox.dataset.initialState) {
                    checkbox.dataset.initialState = checkbox.checked;
                }
                checkbox.disabled = true;
                checkbox.checked = false;
                hasConflicts = true;
            } else {
                checkbox.nextElementSibling.querySelector('a').style.color = '';
                if (checkbox.dataset.initialState !== undefined) {
                    checkbox.checked = checkbox.dataset.initialState === 'true';
                    delete checkbox.dataset.initialState;
                }
                checkbox.disabled = false;
            }
        });

        if (hasConflicts) {
            conflictMessage.style.display = 'block';
        } else {
            conflictMessage.style.display = 'none';
        }

        fileListDiv.empty();

        if (selectedFiles.length > 0) {
            var fileNames = selectedFiles.map(function(file) {
                return file.name;
            });

            fileNames.forEach(function(fileName) {
                var paragraph = '<li class="list-group-item">' + fileName + '<br>' + '</li>'
                fileListDiv.append(paragraph);
            });
        }
    }

    $("#ficheiros").on("change", function(e) {
        var fileListDiv = $('#fileList');
        var conflictMessage = document.getElementById('conflictMessage');
        var checkboxes = document.querySelectorAll('input[name="selectedFiles[]"]');

        handleFileChange(e.currentTarget, fileListDiv, conflictMessage, checkboxes);
    });

    $("#ficheiros_en").on("change", function(e) {
        var fileListDiv = $('#fileList_en');
        var conflictMessage = document.getElementById('conflictMessageEn');
        var checkboxes = document.querySelectorAll('input[name="selectedFilesEn[]"]');

        handleFileChange(e.currentTarget, fileListDiv, conflictMessage, checkboxes);
    });
</script>

<?php
mysqli_close($conn);
?>