<?php
include 'config/dbconnection.php';
include 'models/functions.php';

?>

<?= template_header('Publicações'); ?>
<section class='product_section layout_padding'>
    <div style='padding-top: 50px; padding-bottom: 30px;'>
        <div class='container'>
            <div class='heading_container3'>
                <h3 class="heading_h3" style="text-transform: uppercase;">
                    <?= change_lang("publications-page-heading") ?>
                </h3><br><br>
                <?php
                $pdo = pdo_connect_mysql();
                if (!isset($_SESSION["lang"])) {
                    $lang = "pt";
                } else {
                    $lang = $_SESSION["lang"];
                }
                $valorSiteName = "valor_site_$lang";
                $query = "SELECT distinct dados, YEAR(data) AS publication_year, p.tipo, pt.$valorSiteName FROM publicacoes p
                                LEFT JOIN publicacoes_tipos pt ON p.tipo = pt.valor_API
                                WHERE visivel = true
                                ORDER BY publication_year DESC, pt.$valorSiteName, data DESC";
                $stmt = $pdo->prepare($query);
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

                <div id="publications">
                    <?php foreach ($groupedPublicacoes as $year => $yearPublica) : ?>
                        <div class="mb-5">
                            <b><?= $year ?></b><br>
                            <?php foreach ($yearPublica as $site => $publicacoes) : ?>
                                <div style="margin-left: 10px;" class="mt-3"><b><?= $site ?></b><br></div>
                                <div style="margin-left: 20px;" id="publications<?= $year ?><?= $site ?>">
                                    <?php 
                                        foreach ($publicacoes as $publicacao) : 
                                    ?>
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
        </div>
    </div>
</section>

<?= template_footer(); ?>