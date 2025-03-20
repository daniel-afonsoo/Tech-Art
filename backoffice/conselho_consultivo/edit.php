<?php
require "../verifica.php";
require "../config/basedados.php";

//Se o utilizador não é um administrador ou o próprio que quer editar
if ($_SESSION["autenticado"] != 'administrador' && $_SESSION["autenticado"] != $_GET["id"]) {
    header("Location: index.php");
    exit;
}

// Diretório para upload das fotos
$filesDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\conselho_consultivo\\";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe dados do formulário
    $id   = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $sobre = $_POST["sobre"];

    // Garante que 'ativo' será 0 ou 1
    $ativo = isset($_POST["estado_id"]) ? intval($_POST["estado_id"]) : 0;

    // Monta SQL base
    $sql = "UPDATE conselho_consultivo
               SET nome = ?, email = ?, sobre = ?, ativo = ?";

    // Parâmetros para bind
    $params = [$nome, $email, $sobre, $ativo];

    // Verifica se enviaram nova foto
    $fotografia_exists = isset($_FILES["fotografia"]) && $_FILES["fotografia"]["size"] != 0;
    if ($fotografia_exists) {
        $fotografia = uniqid() . '_' . $_FILES["fotografia"]["name"];
        $sql .= ", fotografia = ? ";
        $params[] = $fotografia;
        // Move o ficheiro para o diretório
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $filesDir . $fotografia);
    }

    // WHERE
    $sql .= " WHERE id = ?";
    $params[] = $id;

    // Prepara statement
    $stmt = mysqli_prepare($conn, $sql);

    // Construção da string de tipos:
    // - As duas últimas posições do array são integers (ativo, id)
    // - As demais são strings
    $param_types = str_repeat('s', count($params) - 2) . 'ii';

    mysqli_stmt_bind_param($stmt, $param_types, ...$params);

    // Executa
    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }
} else {
    // Modo GET: carregar dados do membro do conselho para edição
    $id = $_GET["id"];

    $sql = "SELECT nome, email, sobre, fotografia, ativo
              FROM conselho_consultivo
             WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Preenche variáveis para mostrar no formulário
    $nome       = $row["nome"];
    $email      = $row["email"];
    $sobre      = $row["sobre"];
    $fotografia = $row["fotografia"];
    $ativo      = $row["ativo"];
}

mysqli_close($conn);
?>

<!-- HTML e Formulário -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h5 class="card-header text-center">Editar Membro do Conselho</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="edit.php" method="post" enctype="multipart/form-data">
                
                <!-- Campo hidden para ID -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" minlength="1" required maxlength="100"
                           name="nome" class="form-control"
                           data-error="Por favor Introduza um nome válido"
                           id="inputName" value="<?php echo $nome; ?>">
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>Email</label>
                    <input type="email" minlength="1" required maxlength="100"
                           class="form-control" id="inputEmail" name="email"
                           value="<?php echo $email; ?>">
                    <div class="help-block with-errors"></div>
                </div>

                <div class="row">
                    <div class="col halfCol removeExterno">
                        <div class="form-group">
                            <label>Sobre</label>
                            <textarea type="text" minlength="1" required
                                      data-error="Por favor introduza uma descrição"
                                      class="form-control ck_replace"
                                      id="inputSobre"
                                      placeholder="Sobre"
                                      name="sobre"><?php echo $sobre; ?></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Fotografia</label>
                    <input type="file" accept="image/*" onchange="previewImg(this);" 
                           class="form-control" id="fotografia" name="fotografia">
                    <div class="help-block with-errors"></div>
                </div>
                <img id="preview" src="<?php echo $filesDir . $fotografia; ?>"
                     width='100px' height='100px' class="mb-3" />

                <!-- Campo para controlar se está ativo (1) ou inativo (0) -->
                <div class="form-group estadoMembro">
                    <label>Estado</label>
                    <input type="text" pattern="[0-1]" maxlength="1"
                           class="form-control" name="estado_id" id="estado_id"
                           value="<?php echo $ativo; ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Gravar</button>
                </div>

                <div class="form-group">
                    <button type="button" onclick="window.location.href='index.php'"
                            class="btn btn-danger btn-block">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CKEditor 5 -->
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
</script>
