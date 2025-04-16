<?php
include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();


$language = ($_SESSION["lang"] == "en") ? "_en" : "";
$query = "SELECT id,
        COALESCE(NULLIF(titulo{$language}, ''), titulo) AS titulo,
        COALESCE(NULLIF(conteudo{$language}, ''), conteudo) AS conteudo,
        imagem,data
        FROM noticias WHERE id=? and data<=NOW()";

$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $_GET["noticia"], PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?= template_header('Notícia'); ?>

<section>
    <div class="totall">
        <div class="barraesquerda">
            <h3 class="heading_h3" style="margin-bottom: 20px; padding-top: 60px; padding-right: 10px; padding-left: 45px; word-wrap: break-word;">
                <?= $noticias['titulo'] ?>
            </h3>
            <p style="font-size: 13px; padding-right: 40px; color:#060633; padding-right: 10px; padding-left: 45px;  padding-bottom: 50px;">
                <?php
                $data = strtotime($noticias['data']);
                $diaSemana = date('w', $data);
                $mes = date('n', $data);
                // Format the date using the day-name and month-name arrays from the language dictionary files
                echo change_lang("day-name")[$diaSemana] . ' ' . date('j', $data) . change_lang("date-of") . change_lang("month-name")[$mes - 1] . change_lang("date-of") . date('Y', $data);
                ?>
            </p>

        </div>

        <div class="infoCorpo">
            <img style="object-fit: cover; height:350px; padding-left: 50px; padding-top: 50px;margin-bottom: 30px; max-width: calc(100% - 50px);" src="../backoffice/assets/noticias/<?= $noticias['imagem'] ?>" alt="">

            <div class="textInfo" style="padding-bottom: 80px;">
                <div class="ck-content">
                    <?= $noticias['conteudo'] ?>
                </div>
            </div>

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