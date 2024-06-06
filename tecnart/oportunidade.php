<?php
include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();

$language = ($_SESSION["lang"] == "en") ? "_en" : "";
$query = "SELECT id, imagem, visivel,
COALESCE(NULLIF(titulo{$language}, ''), titulo) AS titulo, 
COALESCE(NULLIF(conteudo{$language}, ''), conteudo) AS conteudo FROM oportunidades WHERE id=? and visivel=true";

$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $_GET["oportunidade"], PDO::PARAM_INT);
$stmt->execute();
$oportunidades = $stmt->fetch(PDO::FETCH_ASSOC);
$id = $oportunidades['id'];
$filesDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\oportunidades\\ficheiros_$id\\" . $_SESSION["lang"] . "\\";
$filesDir1 = "../backoffice/assets/oportunidades/ficheiros_$id/" . $_SESSION["lang"] . "/";
if (is_dir($filesDir)) {
    $files = scandir($filesDir);
    $files = array_diff($files, array('.', '..'));
    if (empty($files)) {
        $filesDir1 = "../backoffice/assets/oportunidades/ficheiros_$id/pt/";«;
        $filesDir = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\oportunidades\\ficheiros_$id\\pt\\";
        $files = scandir($filesDir);
        $files = array_diff($files, array('.', '..'));
    }
}

?>

<?= template_header('Oportunidade'); ?>

<section>
    <div class="totall">
        <div class="barraesquerda">
            <h3 class="heading_h3 pb-5" style="margin-bottom: 20px; padding-top: 60px; padding-right: 10px; padding-left: 45px; word-wrap: break-word;">
                <?= $oportunidades['titulo'] ?>
            </h3>
        </div>

        <div class="infoCorpo">
            <img style="object-fit: cover; height:350px; padding-left: 50px; padding-top: 50px;margin-bottom: 30px; max-width: calc(100% - 50px);" src="../backoffice/assets/oportunidades/<?= $oportunidades['imagem'] ?>" alt="">

            <div class="textInfo">
                <div class="ck-content">
                    <?= $oportunidades['conteudo'] ?>
                </div>


            </div>
                <?php

                if(isset($files)){
                    echo '<h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-right: 10px; padding-left: 50px;">
                    '.change_lang("opport-page-file").'</h3>
                <div class="textInfo" style="padding-bottom: 80px;">';
                    foreach ($files as $file) {
                        echo '<a href="' . $filesDir1 . '/' . $file . '" target="_blank">' . $file . '</a><br>';
                    }
                }
                echo '</div>';
                ?>
        </div>



    </div>
</section>

</div>

</div>
</div>


</section>

<?= template_footer(); ?>

<script>
</script>
</body>

</html>