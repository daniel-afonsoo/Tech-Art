<?php
require "../verifica.php";
require "../config/basedados.php";

$mainDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\projetos\\";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $sobreprojeto = $_POST["sobreprojeto"];
    $referencia = $_POST["referencia"];
    $areapreferencial = $_POST["areapreferencial"];
    $financiamento = $_POST["financiamento"];
    $ambito = $_POST["ambito"];
    $concluido = isset($_POST['concluido']) ? 1 : 0;
    $site = $_POST["site"];
    $facebook = $_POST["facebook"];
    $investigadores = $_POST["investigadores"] ?? [];
    $investigador_principal = $_POST["investigador_principal"] ?? null;
    $nome_en = $_POST["nome_en"];
    $descricao_en = $_POST["descricao_en"];
    $sobreprojeto_en = $_POST["sobreprojeto_en"];
    $referencia_en = $_POST["referencia_en"];
    $areapreferencial_en = $_POST["areapreferencial_en"];
    $financiamento_en = $_POST["financiamento_en"];
    $ambito_en = $_POST["ambito_en"];
    $site_en = $_POST["site_en"];
    $facebook_en = $_POST["facebook_en"];

    $sql = "UPDATE projetos SET nome = ?, descricao = ?, sobreprojeto = ?, referencia = ?, areapreferencial = ?, financiamento = ?, ambito = ?, site = ?, facebook = ?, nome_en = ?, descricao_en = ?, sobreprojeto_en = ?, referencia_en = ?, areapreferencial_en = ?, financiamento_en = ?, ambito_en = ?, site_en = ?, facebook_en = ?, concluido = ? WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssssssssssssisssi', 
    $nome, $descricao, $sobreprojeto, $referencia, 
    $areapreferencial, $financiamento, $ambito, $site, $facebook, 
    $nome_en, $descricao_en, $sobreprojeto_en, $referencia_en, 
    $areapreferencial_en, $financiamento_en, $ambito_en, 
    $site_en, $facebook_en, $concluido, $id
);

    if (mysqli_stmt_execute($stmt)) {
        $sql = "DELETE FROM investigadores_projetos WHERE projetos_id = ?";
        $stmt_delete = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_delete, "i", $id);
        mysqli_stmt_execute($stmt_delete);

        if (count($investigadores) > 0) {
            foreach ($investigadores as $investigadorid) {
                $isPrincipal = ($investigadorid == $investigador_principal) ? 1 : 0;
                $sqlinsert = "INSERT INTO investigadores_projetos (investigadores_id, projetos_id, investigador_principal) VALUES (?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $sqlinsert);
                mysqli_stmt_bind_param($stmt_insert, "iii", $investigadorid, $id, $isPrincipal);
                mysqli_stmt_execute($stmt_insert);
            }
        }
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao atualizar projeto: " . mysqli_error($conn);
    }
}



else {

    $sql = "SELECT * from projetos where id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $id = $_GET["id"];

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $nome = $row["nome"];
    $descricao = $row["descricao"];
    $sobreprojeto = $row["sobreprojeto"];
    $referencia = $row["referencia"];
    $fotografia = $row["fotografia"];
    $areapreferencial = $row["areapreferencial"];
    $financiamento = $row["financiamento"];
    $ambito = $row["ambito"];
    $concluido = $row["concluido"] ? "checked" : "";
    $site = $row["site"];
    $facebook = $row["facebook"];
    $nome_en = $row["nome_en"];
    $descricao_en = $row["descricao_en"];
    $sobreprojeto_en = $row["sobreprojeto_en"];
    $referencia_en = $row["referencia_en"];
    $areapreferencial_en = $row["areapreferencial_en"];
    $financiamento_en = $row["financiamento_en"];
    $ambito_en = $row["ambito_en"];
    $site_en = $row["site_en"];
    $facebook_en = $row["facebook_en"];
}

// Buscar o investigador principal do projeto
$sql = "SELECT investigadores_id FROM investigadores_projetos WHERE projetos_id = ? AND investigador_principal = 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$investigador_principal_selecionado = $row['investigadores_id'] ?? null;



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
            $('#preview').attr('src', '<?= $mainDir . $fotografia ?>');
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
        <h5 class="card-header text-center">Editar Projeto</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value=<?php echo $id; ?>>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="concluido" name="concluido" <?= $concluido ?>>
                        <label class="form-check-label" for="concluido">
                            Concluído
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" minlength="1" required maxlength="100" required data-error="Por favor introduza um nome válido" name="nome" class="form-control" id="inputName" value="<?php echo $nome; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Nome (Inglês)</label>
                            <input type="text" maxlength="100" name="nome_en" class="form-control" id="inputNameEn" value="<?php echo $nome_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea class="form-control" minlength="1" required maxlength="200" data-error="Por favor introduza uma descrição" id="inputDescricao" name="descricao"><?php echo $descricao; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Descrição (Inglês)</label>
                            <textarea class="form-control" maxlength="200" id="inputDescricaoEn" name="descricao_en"><?php echo $descricao_en; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Sobre Projeto</label>
                            <textarea class="form-control ck_replace" minlength="1" required data-error="Por favor introduza um 'sobre projeto'" cols="30" rows="5" id="inputSobreProjeto" name="sobreprojeto"><?php echo $sobreprojeto; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>

                    </div>
                    <div class="col halfCol">
                        <div class="form-group">
                            <label>Sobre Projeto (Inglês)</label>
                            <textarea class="form-control ck_replace" cols="30" rows="5" id="inputSobreProjetoEn" name="sobreprojeto_en"><?php echo $sobreprojeto_en; ?></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Referência</label>
                            <input type="text" minlength="1" required maxlength="100" data-error="Por favor introduza uma referência válida" class="form-control" id="inputReferencia" name="referencia" value="<?php echo $referencia; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Referência (Inglês)</label>
                            <input type="text" maxlength="100" class="form-control" id="inputReferenciaEn" name="referencia_en" value="<?php echo $referencia_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>TECHN&ART área preferencial</label>
                            <input type="text" minlength="1" required maxlength="255" data-error="Por favor introduza uma área preferencial" class="form-control" id="inputAreaPreferencial" name="areapreferencial" value="<?php echo $areapreferencial; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>TECHN&ART área preferencial (Inglês)</label>
                            <input type="text" maxlength="255" class="form-control" id="inputAreaPreferencialEn" name="areapreferencial_en" value="<?php echo $areapreferencial_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Financiamento</label>
                            <input type="text" minlength="1" required maxlength="20" data-error="Por favor introduza um financiamento válido" class="form-control" id="inputFinanciamento" name="financiamento" value="<?php echo $financiamento; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Financiamento (Inglês)</label>
                            <input type="text" maxlength="20" class="form-control" id="inputFinanciamentoEn" name="financiamento_en" value="<?php echo $financiamento_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Âmbito</label>
                            <input type="text" minlength="1" required maxlength="100" data-error="Por favor introduza um âmbito válido" class="form-control" id="inputAmbito" name="ambito" value="<?php echo $ambito; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Âmbito (Inglês)</label>
                            <input type="text" maxlength="100" class="form-control" id="inputAmbitoEn" name="ambito_en" value="<?php echo $ambito_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Site</label>
                            <input type="text" minlength="1" maxlength="100" data-error="Por favor introduza um site válido" class="form-control" id="inputSite" name="site" value="<?php echo $site; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Site (Inglês)</label>
                            <input type="text" maxlength="100" class="form-control" id="inputSiteEn" name="site_en" value="<?php echo $site_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" minlength="1" maxlength="100" data-error="Por favor introduza um facebook válido" class="form-control" id="inputFace" name="facebook" value="<?php echo $facebook; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Facebook (Inglês)</label>
                            <input type="text" maxlength="100" class="form-control" id="inputFaceEn" name="facebook_en" value="<?php echo $facebook_en; ?>">
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>



<!--Modificações Efetuadas Aqui -->
<!--As checkboxes foram substituídas por um elemento <select> com o atributo multiple.
A funcionalidade de múltipla seleção é mantida pelo uso de multiple 
Cada opção da dropdown representa um investigador, permitindo uma interface mais compacta e organizada
-->

<div class="form-group">
                    <label>Investigadores/as</label><br>
                    <select id="investigadores" name="investigadores[]" class="form-control" multiple>
                    <?php
                    $sql = "SELECT investigadores_id FROM investigadores_projetos WHERE projetos_id = " . $id;
                    $result = mysqli_query($conn, $sql);
                    $selected = array();
                    if (mysqli_num_rows($result) > 0) {
                        while (($row =  mysqli_fetch_assoc($result))) {
                            $selected[] = $row['investigadores_id'];
                        }
                    }
                    $sql = "SELECT id, nome, tipo FROM investigadores 
                            ORDER BY CASE WHEN tipo = 'Externo' THEN 1 ELSE 0 END, tipo, nome;";
                    $result = mysqli_query($conn, $sql);
                    // Cria um menu suspenso que permite a seleção de múltiplos investigadores, mostrando aqueles que estão associados a um projeto específico como opções pré-selecionadas.
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            //"in_array($row['id'], $selected)":Esta função verifica se o ID do investigador está no array $selected.Se estiver no array, $isSelected recebe a string "selected" 
                           // isso fará com que a opção apareça como selecionada no menu. Se não estiver, $isSelected recebe uma string vazia ""
                            $isSelected = in_array($row['id'], $selected) ? "selected" : "";
                            //echo "<option ...": Cria uma opção no menu, mostrando o nome do investigador e marcando-a como selecionada, se necessário
                            echo "<option value='" . $row['id'] . "' $isSelected>" . $row['nome'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

                       <!--Investigador Principal-->
            <div class="form-group">
              <label>Investigador Principal</label>
              <select id="investigador_principal" name="investigador_principal" class="form-control">
              <option value="">Selecione o investigador principal</option>
            <?php
               $sql = "SELECT id, nome FROM investigadores ORDER BY nome;";
               $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
            // Verifica se o investigador é o principal e adiciona "selected" na opção correta
            $selected = ($row['id'] == $investigador_principal_selecionado) ? "selected" : "";
            echo "<option value='" . $row['id'] . "' $selected>" . $row['nome'] . "</option>";
            }
            ?>
          </select>
     </div>


<!-- Carregar o arquivo CSS do Select2, que estiliza o componente de seleção. Isso faz com que o menu de seleção tenha um visual mais moderno e atraente, em vez do estilo padrão dos navegadores. -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!--Carrega o arquivo JavaScript do Select2, que fornece a funcionalidade interativa necessária para transformar o menu de seleção-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--Script para Inicializar o Select2-->
<script>
    $(document).ready(function() {
        $('#investigadores').select2({
            placeholder: "Selecione os investigadores",
            allowClear: true,
        });
    });
</script>
<!--O Select2 transforma o menu de seleção num componente mais bonito e interativo.Proporciona uma interface mais amigável e responsiva em comparação com o menu de seleção padrão-->
<!--O Select2 estiliza automaticamente a opção que é marcada como "selected" no HTML. Quando a opção é renderizada, o Select2 aplica estilos apropriados para indicar visualmente 
qual a opção que está selecionada. Quando o user abre o menu, as opções selecionadas aparecerão destacadas, tornando claro quais investigadores estão associados ao projeto.-->


<!--Modificações Efetuadas até Aqui -->
                <div class="form-group">
                    <label>Fotografia</label>
                    <input accept="image/*" type="file" onchange="previewImg(this);" class="form-control" id="inputFotografia" name="fotografia" value=<?php echo $fotografia; ?>>
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                    <img id="preview" src="<?php echo $mainDir  . $fotografia; ?>" class="mt-3" width='100px' height='100px' />
                </div>



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