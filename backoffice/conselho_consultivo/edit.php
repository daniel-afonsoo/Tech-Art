<?php
require "../verifica.php";
require "../config/basedados.php";
//Se o utilizador não é um administrador ou o proprio que quer editar
if ($_SESSION["autenticado"] != 'administrador' && $_SESSION["autenticado"] != $_GET["id"]) {
    header("Location: index.php");
}
//$filesDir = "../assets/conselho_consultivo/";
$filesDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\conselho_consultivo\\";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $sobre = $_POST["sobre"];
    $id = $_POST["id"];
    

    $sql = "UPDATE conselho_consultivo set nome = ?, email = ?, sobre = ?";
    $params = [$nome, $email, $sobre,];
    $fotografia_exists = isset($_FILES["fotografia"]) && $_FILES["fotografia"]["size"] != 0;
    if ($fotografia_exists) {

        $fotografia = uniqid() . '_' . $_FILES["fotografia"]["name"];
        $sql .= ", fotografia = ? ";
        $params[] = $fotografia;
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $filesDir . $fotografia);
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
    $sql = "SELECT nome, email, sobre, fotografia from conselho_consultivo WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $id = $_GET["id"];

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $nome = $row["nome"];
    $email = $row["email"];
    $sobre = $row["sobre"];
    $fotografia = $row["fotografia"];

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
            $('#preview').attr('src', '<?= $filesDir . $fotografia ?>');
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

    textarea {
        min-height: 100px;
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
        <h5 class="card-header text-center">Editar Investigador</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="edit.php" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value=<?php echo $id; ?>>
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" minlength="1" required maxlength="100" name="nome" class="form-control" data-error="Por favor Introduza um nome válido" id="inputName" value="<?php echo $nome; ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>Email</label>
                    <input type="email" minlength="1" required maxlength="100" class="form-control" id="inputEmail" name="email" value="<?php echo $email; ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="row">
                    <div class="col halfCol removeExterno">
                        <div class="form-group">
                            <label>Sobre</label>
                            <!--Mudanças Efetuadas Aqui-->
                            <!--A class do Sobre passa a class="form-control ck_replace" para incluir o CKEditor-->
                            <textarea type="text" minlength="1" required data-error="Por favor introduza uma descrição sobre si" class="form-control ck_replace" id="inputSobre" placeholder="Sobre" name="sobre"><?php echo $sobre; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label>Fotografia</label>
                    <input type="file" accept="image/*" onchange="previewImg(this);" class="form-control" id="fotografia" name="fotografia" value="<?php echo $fotografia; ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>
                <img id="preview" src="<?php echo $filesDir . $fotografia; ?>" width='100px' height='100px' class="mb-3" />
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

<!-- Novas Funcionalidades-->
<!--Criar o CKEditor 5-->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
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


    window.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        let lastChecked;

        function handleCheck(event) {
            if (event.shiftKey) {
                let start = Array.from(checkboxes).indexOf(this);
                let end = Array.from(checkboxes).indexOf(lastChecked);
                if (start > end) {
                    [start, end] = [end, start];
                }
                checkboxes.forEach((checkbox, index) => {
                    if (index >= start && index <= end) {
                        checkbox.checked = this.checked;
                    }
                });
            }

            lastChecked = this;
        }

        checkboxes.forEach(checkbox => checkbox.addEventListener('click', handleCheck));
    });
</script>
<?php
mysqli_close($conn);
?>


