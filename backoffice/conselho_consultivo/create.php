<?php
require "../verifica.php";
require "../config/basedados.php";
//Se o utilizador não é um administrado
if ($_SESSION["autenticado"] != "administrador") {
    //não tem permissão para criar um novo investigador
    header("Location: index.php");
    exit;
}
//$filesDir = "../assets/conselho_consultivo/";
$filesDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\conselho_consultivo\\";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['password'] == $_POST['repeatPassword']) {

        $target_file = uniqid() . '_' . $_FILES["fotografia"]["name"];
        //transferir a imagem para a pasta de assets
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], $filesDir . $target_file);

        $sql = "INSERT INTO conselho_consultivo (nome, email, sobre, fotografia, password) " .
            "VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss', $nome, $email,$sobre,$fotografia, $password);
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $sobre = $_POST["sobre"];
        $fotografia = $target_file; 


        $ativo = isset($_POST["estado"]) && $_POST["estado"] == "1" ? 1 : 0;
        if ($_POST["password"] == null || $_POST["password"] == '') {
            $_POST["password"] = substr(str_shuffle(strtolower(sha1(rand() . time()))), 0, $PASSWORD_LENGTH);
        }
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: index.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: Passwords não são iguais";
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
                $('#preview').show();
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#preview').attr('src', '');
            $('#preview').hide();
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
</style>

<div class="container-xl mt-5">
    <div class="card">
        <h5 class="card-header text-center">Adicionar Elemento no Conselho Consultivo</h5>
        <div class="card-body">
            <form role="form" data-toggle="validator" action="create.php" method="post" enctype="multipart/form-data">


                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" minlength="1" required maxlength="100" name="nome" class="form-control" data-error="Por favor introduza um nome válido" id="inputName" placeholder="Nome">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>Email</label>
                    <input type="email" minlength="1" required maxlength="100" class="form-control" id="inputEmail" placeholder="Email" name="email">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group removeExterno">
                    <label>Password</label>
                    <input type="password" minlength="5" required maxlength="255" data-error="Por favor introduza uma password com mínimo de 5 caracteres" class="form-control" id="inputPassword" placeholder="Password" name="password">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>


                <div class="form-group removeExterno">
                    <label for="repeatPassword">Repetir a Password</label>
                    <input type="password" class="form-control" id="repeatPassword" placeholder="Repetir Password" required name="repeatPassword" data-error="As Passwords são diferentes">
                    <div class="help-block with-errors"></div>
                </div>

                <div class="row removeExterno">
                    <div class="col halfCol removeExterno">
                        <div class="form-group">
                            <label>Sobre</label>
                            <textarea type="text" minlength="1" required data-error="Por favor introduza uma descrição sobre si" class="form-control" id="inputSobre" placeholder="Sobre" name="sobre"></textarea>
                            <!-- Error -->
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Fotografia</label>
                    <input type="file" accept="image/*" onchange="previewImg(this);" minlength="1" maxlength="100" required data-error="Por favor adicione uma fotografia válida" class="form-control" id="inputFotografia" placeholder="Fotografia" name="fotografia">
                    <!-- Error -->
                    <div class="help-block with-errors"></div>
                </div>
                <img id="preview" style="display: none;" width='100px' height='100px' class="mb-3" />

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
<script>
    //Ao retirar o foco do input de password repetida, verifique se ao input corresponde ao input de password
    $("#repeatPassword").focusout(function() {
        var input = document.getElementById('repeatPassword');
        if (input.value != document.getElementById('inputPassword').value) {
            input.setCustomValidity("The Passwords don't match");
            input.setCustomValidity("The Passwords don't match");

        } else {
            input.setCustomValidity('');
        }
    });

    
</script>


<?php
mysqli_close($conn);
?>