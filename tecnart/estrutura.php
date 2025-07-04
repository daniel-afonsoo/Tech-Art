<?php
include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();

registar_visita($pdo, "estrutura.php");

redirectPageLanguage("estrutura.php", "structure.php");

// Buscar o conteúdo completo da estrutura
$stmt = $pdo->prepare('SELECT * FROM conselho_consultivo');
$stmt->execute();
$conselho_consultivo_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>

<?= template_header('Estrutura'); ?>

<!-- product section -->
<section class="product_section layout_padding">
    <div style="padding-top: 50px; padding-bottom: 30px;">
        <div class="container">
            <div class="heading_container3">

                <h3 style="font-size: 33px; text-transform: uppercase;">
                    <?= get_titulo_estrutura(1) ?>
                </h3>

                <div class="flex-container mobile_reverse">
                    <div class="flex-left">
                        <figure class="imgfigura">
                            <img class="imgestrutura w-100" style="max-width:330px;" src="./assets/images/estrutura.jpg" alt="Boat">
                            <figcaption class="imgs"></figcaption>
                        </figure>
                    </div>
                    <div class="flex-right">
                        <?= get_text_estrutura(1) ?>
                        <section class="product_section layout_padding">
                            <div style="padding-top: 20px;">
                                <div class="container">
                                    <div class="row justify-content-center mt-3">
                                        <?php foreach ($conselho_consultivo_members as $member): ?>
                                            <div class="ml-5 imgList">
                                                <a href="cons.php?id=<?= $member['id'] ?>">
                                                    <div class="image_default">
                                                        <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/<?= $member['fotografia'] ?>" alt="<?= $member['nome'] ?>">
                                                        <div class="imgText justify-content-center m-auto"><?= $member['nome'] ?></div>
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
        </div>
    </div>
</section>
<!-- end product section -->

<?= template_footer(); ?>

</body>

</html>