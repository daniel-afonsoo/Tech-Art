<?php
include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();
$language = ($_SESSION["lang"] == "en") ? "_en" : "";
$query = "SELECT id, email, nome,
        COALESCE(NULLIF(sobre{$language}, ''), sobre) AS sobre,
        COALESCE(NULLIF(areasdeinteresse{$language}, ''), areasdeinteresse) AS areasdeinteresse,
        ciencia_id, tipo, fotografia, orcid, scholar, research_gate, scopus_id
        FROM investigadores WHERE id=? and tipo = \"Colaborador\" ";
$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $_GET["colaborador"], PDO::PARAM_INT);
$stmt->execute();
$investigadores = $stmt->fetch(PDO::FETCH_ASSOC);
$id =  $_GET["colaborador"];
?>

<?= template_header('Colaborador/a'); ?>

<!-- product section -->
<section>
    <div class="totall">
        <div class="barraesquerda">
            <h3 class="heading_h3" style="font-size: 38px; margin-bottom: 20px; padding-right: 10px; padding-top: 60px; padding-left: 60px; max-width: calc(100% - 50px); word-wrap: break-word;">
                <?= $investigadores['nome'] ?>
            </h3>
            <div class="canvasEmail" style="height:150px; padding-right: 10px;">

                <div class="emailScroll">
                    <canvas id="canvas"></canvas>
                    <script>
                        const ratio = Math.ceil(window.devicePixelRatio);
                        const canvas = document.getElementById("canvas");
                        const txt = "<?= $investigadores['email'] ?>";
                        const context = canvas.getContext("2d");
                        context.font = "15px 'Montserrat', sans-serif";

                        width = context.measureText(txt).width + 5
                        height = canvas.offsetHeight

                        canvas.width = width * ratio;
                        canvas.height = height * ratio;
                        canvas.style.width = `${width}px`;
                        canvas.style.height = `${height}px`;

                        context.font = "15px 'Montserrat', sans-serif";
                        context.fillStyle = "#060633";
                        context.setTransform(ratio, 0, 0, ratio, 0, 0);
                        context.fillText(txt, 0, 20);
                    </script>
                </div>
            </div>

            <button class="divbotao" id="showit">
                <span href="#" class="innerButton">
                    <?= change_lang("about-btn-class") ?>

                </span>
            </button>

            <button class="divbotao" id="showit2">
                <span href="#" class="innerButton">
                    <?= change_lang("areas-btn-class") ?>
                </span>
            </button>

            <button class="divbotao" id="showit3">
                <span href="#" class="innerButton">
                    <?= change_lang("publications-btn-class") ?>
                </span>
            </button>

            <button class="divbotao lastBtn" id="showit4">
                <span href="#" class="innerButton">
                    <?= change_lang("projects-btn-class") ?>
                </span>
            </button>

            <h5 class="nofinal">
                <?= change_lang("ext-links") ?>
            </h5>

            <div class="alinhado">
                <?= !empty(trim($investigadores['orcid']) . "") ? "<a target='_blank' class='link_externo orcid' href='https://orcid.org/" . $investigadores['orcid'] . "'></a>" : "" ?>
                <?= !empty(trim($investigadores['ciencia_id']) . "") ? "<a target='_blank' class='link_externo ciencia_id' href='https://www.cienciavitae.pt/" . $investigadores['ciencia_id'] . "'></a>" : "" ?>
                <?= !empty(trim($investigadores['research_gate']) . "") ? "<a target='_blank' class='link_externo research_gate' href=" . $investigadores['research_gate'] . "></a>" : "" ?>
            </div>
        </div>
        <div id="resto" class="infoCorpo">
            <img style="object-fit: cover; width:255px; height:310px; padding-left: 50px; padding-top: 50px" src="../backoffice/assets/investigadores/<?= $investigadores['fotografia'] ?>" alt="">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-left: 50px;">
                <?= change_lang("about-tab-title-class") ?>
            </h3>

            <div class="textInfo" style="padding-bottom: 80px;">
                <?= $investigadores['sobre'] ?>
            </div>

        </div>

        <div id="resto2" class="infoCorpo" style="display: none;">
            <img style="object-fit: cover; width:255px; height:310px; padding-left: 50px; padding-top: 50px" src="../backoffice/assets/investigadores/<?= $investigadores['fotografia'] ?>" alt="">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-left: 50px;">
                <?= change_lang("areas-tab-title-class") ?>
            </h3>

            <div class="textInfo" style="padding-bottom: 40px;">
                <?= $investigadores['areasdeinteresse'] ?>
            </div>

        </div>

        <div id="resto3" class="infoCorpo" style="display: none;">
            <img style="object-fit: cover; width:255px; height:310px; padding-left: 50px; padding-top: 50px" src="../backoffice/assets/investigadores/<?= $investigadores['fotografia'] ?>" alt="">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-left: 50px;">
                <?= change_lang("publications-tab-title-class") ?>
            </h3>

            <?php
            $pdo = pdo_connect_mysql();
            if (!isset($_SESSION["lang"])) {
                $lang = "pt";
            } else {
                $lang = $_SESSION["lang"];
            }
            $valorSiteName = "valor_site_$lang";
            $query = "SELECT dados, YEAR(data) AS publication_year, p.tipo, pt.$valorSiteName FROM publicacoes p
                                LEFT JOIN publicacoes_tipos pt ON p.tipo = pt.valor_API
                                INNER JOIN publicacoes_investigadores AS pi ON p.idPublicacao = pi.publicacao
                                WHERE visivel = true AND pi.investigador = :investigatorId
                                ORDER BY publication_year DESC, pt.$valorSiteName, data DESC";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':investigatorId', $id, PDO::PARAM_INT);
            $stmt->execute();
            $publicacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $groupedPublicacoes = array();
            foreach ($publicacoes as $publicacao) {
                $year = $publicacao['publication_year'];
                if ($year == null) {
                    $year = change_lang("year-unknown");
                }

                $site = $publicacao[$valorSiteName];

                if (!isset($groupedPublicacoes[$year])) {
                    $groupedPublicacoes[$year] = array();
                }

                if (!isset($groupedPublicacoes[$year][$site])) {
                    $groupedPublicacoes[$year][$site] = array();
                }

                $groupedPublicacoes[$year][$site][] = $publicacao['dados'];
            }
            ?>
            <script src="../backoffice/assets/js/citation-js-0.6.8.js"></script>
            <script>
                const Cite = require('citation-js');
            </script>

            <div id="publications" class='textInfo' style='padding-bottom: 10px;'>
                <?php foreach ($groupedPublicacoes as $year => $yearPublica) : ?>
                    <div class="mb-5">
                        <b><?= $year ?></b><br>
                        <?php foreach ($yearPublica as $site => $publicacoes) : ?>
                            <div style="margin-left: 10px;" class="mt-3"><b><?= $site ?></b><br></div>
                            <div style="margin-left: 20px;" id="publications<?= $year ?><?= $site ?>">
                                <?php foreach ($publicacoes as $publicacao) : ?>
                                    <script>
                                        var formattedCitation = new Cite(<?= json_encode($publicacao) ?>).format('bibliography', {
                                            format: 'html',
                                            template: 'apa',
                                            lang: 'en-US'
                                        });;
                                        var citationContainer = document.createElement('div');
                                        citationContainer.innerHTML = formattedCitation;
                                        citationContainer.classList.add('mb-3');
                                        document.getElementById('publications<?= $year ?><?= $site ?>').appendChild(citationContainer);
                                    </script>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div><br>
                <?php endforeach; ?>
            </div>



        </div>

        <div id="resto4" class="infoCorpo" style="display: none;">
            <img style="object-fit: cover; width:255px; height:310px; padding-left: 50px; padding-top: 50px" src="../backoffice/assets/investigadores/<?= $investigadores['fotografia'] ?>" alt="">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-left: 50px;">
                <?= change_lang("projects-tab-title-class") ?>
            </h3>

            <div class="textInfo" style="padding-bottom: 20px;">
                <?php
                $query = "SELECT p.id, COALESCE(NULLIF(p.nome{$language}, ''), p.nome) AS nome, p.fotografia 
                FROM investigadores_projetos ip 
                INNER JOIN projetos p ON p.id = ip.projetos_id 
                WHERE ip.investigadores_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(1, $_GET["colaborador"], PDO::PARAM_INT);
                $stmt->execute();
                $projetos = $stmt->fetchall(PDO::FETCH_ASSOC);
                ?>
                <section class="product_section layout_padding">
                    <div style="padding-top: 20px;">
                        <div class="container">
                            <div class="row justify-content-center mt-3">
                                <?php foreach ($projetos as $projeto) : ?>

                                    <div class="ml-5 imgList">
                                        <a href="projeto.php?projeto=<?= $projeto['id'] ?>">
                                            <div class="image_default">
                                                <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="../backoffice/assets/projetos/<?= $projeto['fotografia'] ?>" alt="">
                                                <div class="imgText justify-content-center m-auto">
                                                    <?= $projeto['nome'] ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                <?php endforeach; ?>

                            </div>


                        </div>

                    </div>
                </section>

            </div>

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
            $('#resto3').hide();
            $('#resto4').hide();
        });

        $('button#showit2').on('click', function() {
            $('#resto2').show();
            $('#resto').hide();
            $('#resto3').hide();
            $('#resto4').hide();
        });

        $('button#showit3').on('click', function() {
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
        });

    });
</script>
</body>

</html>