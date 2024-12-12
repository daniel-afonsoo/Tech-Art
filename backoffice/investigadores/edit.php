<?php
require "../verifica.php";
require "../config/basedados.php";
//Se o utilizador não é um administrador ou o proprio que quer editar
if ($_SESSION["autenticado"] != 'administrador' && $_SESSION["autenticado"] != $_GET["id"]) {
    header("Location: index.php");
}
//$filesDir = "../assets/investigadores/";
$filesDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\investigadores\\";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $ciencia_id = $_POST["ciencia_id"];
    $sobre = $_POST["sobre"];
    $sobre_en = $_POST["sobre_en"];
    $tipo = $_POST["tipo"];
    $id = $_POST["id"];
    $areasdeinteresse = $_POST["areasdeinteresse"];
    $areasdeinteresse_en = $_POST["areasdeinteresse_en"];
    $orcid = $_POST["orcid"];
    $scholar = $_POST["scholar"];
    $research_gate = $_POST["research_gate"];
    $scopus_id = $_POST["scopus_id"];


    $sql = "UPDATE investigadores set nome = ?, email = ?, ciencia_id = ?, sobre = ?, sobre_en = ?, areasdeinteresse = ?, areasdeinteresse_en = ?, tipo = ?, orcid = ?, scholar = ?, research_gate=?, scopus_id=?";
    $params = [$nome, $email, $ciencia_id, $sobre, $sobre_en,  $areasdeinteresse, $areasdeinteresse_en, $tipo, $orcid, $scholar, $research_gate, $scopus_id];
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
    $sql = "SELECT nome, email, ciencia_id, sobre, tipo, fotografia, areasdeinteresse, orcid, scholar, research_gate, scopus_id, sobre_en, areasdeinteresse_en from investigadores WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $id = $_GET["id"];

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $nome = $row["nome"];
    $email = $row["email"];
    $ciencia_id = $row["ciencia_id"];
    $sobre = $row["sobre"];
    $tipo = $row["tipo"];
    $fotografia = $row["fotografia"];
    $areasdeinteresse = $row["areasdeinteresse"];
    $orcid = $row["orcid"];
    $scholar = $row["scholar"];
    $research_gate = $row["research_gate"];
    $scopus_id = $row["scopus_id"];
    $sobre_en = $row["sobre_en"];
    $areasdeinteresse_en = $row["areasdeinteresse_en"];
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
                    <div class="col halfCol removeExterno">
                        <div class="form-group">
                            <label>Sobre (Inglês)</label>
                            <!--Mudanças Efetuadas Aqui-->
                            <!--A class do Sobre(Inglês) passa a class="form-control ck_replace" para incluir o CKEditor-->
                            <textarea type="text" class="form-control ck_replace" id="inputSobreEn" placeholder="Sobre (Inglês)" name="sobre_en"><?php echo $sobre_en; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col removeExterno">
                        <div class="form-group">
                            <label>Áreas de interesse</label>
                            <!--Mudanças Efetuadas Aqui-->
                            <!--A class do Áreas de interesse passa a class="form-control ck_replace" para incluir o CKEditor-->
                            <textarea type="text" minlength="1" required data-error="Por favor introduza as suas áreas de interesse" class="form-control ck_replace" id="inputAreasdeInteresse" placeholder="Áreas de interesse" name="areasdeinteresse"><?php echo $areasdeinteresse; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col removeExterno">
                        <div class="form-group">
                            <label>Áreas de interesse (Inglês)</label>
                            <!--Mudanças Efetuadas Aqui-->
                            <!--A class do Áreas de interesse passa a class="form-control ck_replace" para incluir o CKEditor-->
                            <textarea type="text" class="form-control ck_replace" id="inputAreasdeInteresseEn" placeholder="Áreas de interesse (Inglês)" name="areasdeinteresse_en"><?php echo $areasdeinteresse_en; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tipo</label><br>
                    <select name="tipo" id="tipo">
                        <option value="">--Select--</option>
                        <option value="Integrado" <?php echo  $tipo == "Integrado" ? "selected" : "" ?>>Integrado/a</option>
                        <option value="Colaborador" <?php echo  $tipo == "Colaborador" ? "selected" : "" ?>>Colaborador/a</option>
                        <option value="Aluno" <?php echo  $tipo == "Aluno" ? "selected" : "" ?>>Aluno/a</option>
                        <option value="Externo" <?php echo  $tipo == "Externo" ? "selected" : "" ?>>Externo</option>
                    </select>
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>CiênciaVitae ID</label>
                    <input type="text" minlength="1" required maxlength="100" class="form-control" data-error="Por favor introduza um ID válido" id="inputCienciaid" name="ciencia_id" value="<?php echo $ciencia_id; ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>Orcid</label>
                    <input type="text" minlength="1" required maxlength="255" data-error="Por favor introduza um orcID válido" class="form-control" id="inputOrcid" name="orcid" value="<?php echo $orcid; ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>Scholar</label>
                    <input type="text" minlength="1" maxlength="255" data-error="Por favor introduza im ID válido" class="form-control" id="inputScholar" name="scholar" value="<?php echo $scholar; ?>">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group removeExterno">
                    <label for="research_gate">ResearchGate: </label>
                    <input type="text" class="form-control" name="research_gate" id="research_gate" value="<?= $research_gate ?>">
                </div>
                <div class="form-group removeExterno">
                    <label for="research_gate">ScopusID: </label>
                    <input type="text" class="form-control" name="scopus_id" id="scopus_id" value="<?= $scopus_id ?>">
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
<!--Mudanças até aqui-->



<script>
    // Função para lidar com a alteração do elemento 'select' com o id tipo
    function handleSelectChange() {
        var selectedOption = this.value;
        console.log(selectedOption); // Exibir a opção selecionada no console
        var elementsToHide = document.querySelectorAll('.removeExterno');

        if (selectedOption == 'Externo') {
            // Se a opção 'Externo' for selecionada, ocultar elementos e desativar campos
            elementsToHide.forEach(function(element) {
                // Ocultar o elemento
                element.style.display = 'none';
                // Iterar sobre os campos de entrada e de texto
                element.querySelectorAll('input, textarea').forEach(function(input) {
                    // Guardar o valor atual 
                    input.setAttribute('data-value', input.value);
                    input.setAttribute('data-required', input.required);
                    // Definir campo como somente leitura
                    input.readOnly = true;
                    // Remover a obrigatoriedade
                    input.required = false;
                    input.value = '';
                });
            });
        } else {
            // Se a opção for diferente de 'Externo', mostrar elementos e restaurar campos
            elementsToHide.forEach(function(element) {
                //Apenas alterar os dados se for necessario 
                if (element.style.display != 'none') {
                    return;
                }
                // Mostrar o elemento
                element.style.display = 'block';
                // Iterar sobre os campos de entrada e de texto
                element.querySelectorAll('input, textarea').forEach(function(input) {
                    // Remover a configuração de somente leitura
                    input.readOnly = false;
                    // Restaurar a obrigatoriedade
                    input.required = input.getAttribute('data-required') === 'true';
                    // Restaurar o valor 
                    input.value = input.getAttribute('data-value');
                });
            });
        }
    }

    // Adicionar um event listener para quando carrega
    document.addEventListener("DOMContentLoaded", function() {
        // Chamar a função para adicionar valores iniciais e configurações obrigatórias
        var tipoSelect = document.getElementById('tipo');
        tipoSelect.addEventListener('change', handleSelectChange);
        // Chamar a função para verificar se tem de esconder campos quando a página é carregada
        handleSelectChange.call(tipoSelect);
    });
</script>
<?php
mysqli_close($conn);
?>


