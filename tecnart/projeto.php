<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config/dbconnection.php';
include 'models/functions.php';


// Obter o ID do projeto da query string
if (!isset($_GET["projeto"])) {
    die("O parâmetro 'projeto' está ausente na URL.");
}
$projectId = $_GET["projeto"];

// Redirecionar dinamicamente com base no idioma
redirectPageLanguageWithParam("projeto.php", "project.php", "projeto", $projectId);

$language = ($_SESSION["lang"] == "en") ? "_en" : "";
$pdo = pdo_connect_mysql();
$query = "SELECT id, COALESCE(NULLIF(nome{$language}, ''), nome) AS nome, 
            COALESCE(NULLIF(descricao{$language}, ''), descricao) AS descricao, 
            COALESCE(NULLIF(sobreprojeto{$language}, ''), sobreprojeto) AS sobreprojeto,
            COALESCE(NULLIF(referencia{$language}, ''), referencia) AS referencia, 
            COALESCE(NULLIF(areapreferencial{$language}, ''), areapreferencial) AS areapreferencial, 
            COALESCE(NULLIF(financiamento{$language}, ''), financiamento) AS financiamento, 
            COALESCE(NULLIF(ambito{$language}, ''), ambito) AS ambito, 
            COALESCE(NULLIF(site{$language}, ''), site) AS site,
            COALESCE(NULLIF(facebook{$language}, ''), facebook) AS facebook, 
            fotografia, concluido FROM projetos Where id = ?";
$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $projectId, PDO::PARAM_INT);
$stmt->execute();
$projetos = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>

<?= template_header('Projeto'); ?>

<section class="product_section layout_padding3">
    <div style="height:300px; background: url('../backoffice/assets/projetos/<?= $projetos['fotografia'] ?>'); background-size: cover; background-position:100%;">
    </div>
</section>


<!-- product section -->
<section>

    <div class="totall">
        <div class="barraesquerda">
            <h3 class="heading_h3_projeto">
                <?= $projetos['nome'] ?>
            </h3>
            <div style="font-size: 15px; color:#060633; padding-left: 45px; padding-right: 10px; padding-bottom: 50px; padding-right:20px;">
                <?= $projetos['descricao'] ?>
            </div>



            <button class="divbotao" id="showit">
                <span href="#" class="innerButton">
                    <?= change_lang("about-project-btn-class") ?>
                </span>
            </button>

            <button class="divbotao lastBtn" id="showit2">
                <span href="#" class="innerButton">
                    <?= change_lang("team-steakholders-btn-class") ?>
                </span>
            </button>

            <!-- <button class="divbotao" id="showit3">
                <span href="#" class="innerButton">
                    notícias e eventos
                </span>
            </button>

            <button class="divbotao" id="showit4" style="margin-bottom: 220px;">
                <span href="#" class="innerButton">
                    publicações
                </span>
            </button> -->
            <h5 class="nofinal">
                <?= change_lang("ext-links") ?>
            </h5>

            <div class="alinhado">
                <?= !empty(trim($projetos['site'] . "")) ? "<a target='_blank' class='link_externo site' href='" . $projetos['site'] . "'></a>" : ""; ?>
                <?= !empty(trim($projetos['facebook'] . "")) ? "<a target='_blank' class='link_externo facebook' href='" . $projetos['facebook'] . "'></a>" : ""; ?>
            </div>

        </div>

        <div id="resto" class="infoCorpo">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 60px; padding-left: 50px;">
                <?= change_lang("about-project-tab-title-class") ?>
            </h3>

            <div class="textInfo " style="padding-bottom: 20px;">
                <div class="ck-content">
                    <?= $projetos['sobreprojeto'] ?>
                </div>
            </div>

            <!-- <h4
                style=" font-size: 26px; color:#060633; text-transform: uppercase; padding-left: 50px; padding-bottom: 30px;">
                <?= change_lang("team-steakholders-tab-subtitle-class") ?>
            </h4> -->

            <div class="textInfo" style="margin-bottom: 8px;">
                <b>
                    <?= change_lang("about-project-tab-reference-tag") ?>
                </b>
                <?= $projetos['referencia'] ?>
            </div>

            <div class="textInfo" style="margin-bottom: 8px;">
                <b>
                    <?= change_lang("about-project-tab-main-research-tag") ?>
                </b>
                <?= $projetos['areapreferencial'] ?>
            </div>

            <div class="textInfo" style="margin-bottom: 8px;">
                <b>
                    <?= change_lang("about-project-tab-financing-tag") ?>
                </b>
                <?= $projetos['financiamento'] ?>
            </div>

            <div class="textInfo" style=" padding-bottom: 150px; margin-bottom: 8px;">
                <b>
                    <?= change_lang("about-project-tab-scope-tag") ?>
                </b>
                <?= $projetos['ambito'] ?>
            </div>

        </div>

        <div id="resto2" class="infoCorpo" style="display: none;">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 60px; padding-left: 50px;">
                <?= change_lang("team-steakholders-tab-title-class") ?>
            </h3>

            <div class="textInfo" style="padding-bottom: 20px;">
                <section class="product_section layout_padding">
                    <div style="padding-top: 20px;">
                        <div class="container">
                            <div class="row justify-content-center mt-3">
                                <?php
                                // Buscar todos os investigadores de uma vez, ordenando por investigador principal primeiro
                                $stmt = $pdo->prepare('
                                    SELECT i.*, ip.investigador_principal 
                                    FROM investigadores i 
                                    INNER JOIN investigadores_projetos ip ON ip.projetos_id = ? AND ip.investigadores_id = i.id 
                                    ORDER BY ip.investigador_principal DESC, i.nome ASC
                                ');
                                $stmt->bindParam(1, $_GET["projeto"], PDO::PARAM_INT);
                                $stmt->execute();
                                $todos_investigadores = $stmt->fetchall(PDO::FETCH_ASSOC);
                                ?>
                                
                                <?php foreach ($todos_investigadores as $investigador) : ?>
                                    <div class="ml-5 imgList">
                                        <?php $tipo = strtolower($investigador['tipo']) ?>
                                        <?php if ($tipo != "externo") {
                                            echo "<a href='$tipo.php?$tipo={$investigador['id']}'>";
                                        } ?>
                                        <div class="image_default">
                                            <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="../backoffice/assets/investigadores/<?= $investigador['fotografia'] ?>" alt="">
                                            <div class="imgText justify-content-center m-auto">
                                                <?= $investigador['nome'] ?>
                                                <?php if ($investigador['investigador_principal'] == 1) : ?>
                                                    <br><small style="font-style: italic; color: #666;"><?= change_lang("team-principal-res-tab-title-class") ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if ($tipo != "externo") echo "</a>" ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div style="clear:all;"></div>
        </div>
    </div>

</section>
<!-- end product section -->

<?= template_footer(); ?>
<script>
    $(function() {
        $('button#showit').on('click', function() {
            $('#resto').show();
            $('#resto2').hide();
            /* $('#resto3').hide();
               $('#resto4').hide(); */
        });

        $('button#showit2').on('click', function() {
            $('#resto2').show();
            $('#resto').hide();
            /*  $('#resto3').hide();
                $('#resto4').hide(); */
        });

        /*     $('button#showit3').on('click', function() {
            $('#resto3').show();
            $('#resto').hide();
            $('#resto2').hide();
            $('#resto4').hide();
        });
    
        $('button#showit4').on('click', function() {
            $('#resto4').show();
            $('#resto').hide();
            $('#resto3').hide();
            $('#resto2').hide();
        }); */

    });

 
</script>

</html>