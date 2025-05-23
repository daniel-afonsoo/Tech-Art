<?php
include 'config/dbconnection.php';
include 'models/functions.php';
$pdo = pdo_connect_mysql();

registar_visita($pdo, "regulamentos.php");


$language = ($_SESSION["lang"] == "en") ? "_en" : "";

$stmt = $pdo->prepare("SELECT * FROM documentos WHERE tipo = 'publico'");
$stmt->execute();
$regulamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= redirectPageLanguage("regulamentos.php","regulations.php"); ?>

<!DOCTYPE html>
<html>
    <?= template_header('Missão'); ?>

    <body style="min-height:100vh;display:flex;flex-direction:column;">
    <div style="flex:1;">
        <section class="product_section layout_padding">
            <div style="padding-top: 50px; padding-bottom: 30px;">
                <div class="container">
                    <div class="heading_container3">
                        <h3 style="text-transform: uppercase;">
                            Regulamentos
                        </h3>
                        <div style="margin-top: 25px;">
                        <?php foreach($regulamentos as $regulamento): ?>
                            <?php
                                // Se existir o ficheiro em inglês e o idioma for inglês, usa-o
                                $nome = !empty($regulamento["nome_arquivo$language"]) ? $regulamento["nome_arquivo$language"] : $regulamento["nome_arquivo"];
                                $caminho = !empty($regulamento["caminho$language"]) ? $regulamento["caminho$language"] : $regulamento["caminho"];
                            ?>
                            <div style="margin-top: 20px;">
                                &bull;
                                <a href="../backoffice/documentos/<?= htmlspecialchars($caminho) ?>" target="_blank" style="font-size:1.2em;">
                                    <?= htmlspecialchars($nome) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?= template_footer(); ?>
    </body>
</html>