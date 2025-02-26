<?php
require "../verifica.php";
require "../config/basedados.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['password'] == $_POST['repeatPassword']) {

        $sql = "update conselho_consultivo set password=? where id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $password, $id);
        $id = $_POST["id"];
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
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT email from conselho_consultivo where id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $email = $row["email"];
    } else {
        echo "Error: Utilizador não definido";
    }
}

?>

<HTML>

<BODY>

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
    </style>

    <div class="container-xl mt-5">
        <div class="card">
            <h5 class="card-header text-center">Alterar Password de <?= $email ?></h5>
            <div class="card-body">
                <form role="form" data-toggle="validator" action="resetpassword.php" method="post" enctype="multipart/form-data">


                    <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" minlength="5" required maxlength="255" required data-error="Por favor introduza uma password com mínimo de 5 caracteres" class="form-control" id="inputPassword" placeholder="Password" name="password">
                        <!-- Error -->
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="repeatPassword">Repetir a Password</label>
                        <input type="password" class="form-control" id="repeatPassword" placeholder="Repetir Password" required name="repeatPassword" data-error="As Passwords são diferentes">
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Alterar</button>
                    </div>

                    <div class="form-group">
                        <button type="submit" onclick="window.location.href = 'index.php'" class="btn btn-danger btn-block">Cancelar</button>
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
</BODY>

</HTML>