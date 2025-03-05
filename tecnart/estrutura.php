<?php

include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();



    $pdo = pdo_connect_mysql();

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
                    <?= change_lang("org-struct-page-heading") ?>
                </h3>

                <div class="flex-container mobile_reverse">
                    <div class="flex-left">
                        <figure class="imgfigura">
                            <img class="imgestrutura w-100" style="max-width:330px;" src="./assets/images/estrutura.jpg" alt="Boat">
                            <figcaption class="imgs"></figcaption>
                        </figure>
                    </div>
                    <div class="flex-right">
                        <?= change_lang("org-struct-page-description") ?><br><br>

                        <b><?= change_lang("org-struct-page-director-tag") ?></b><br><?= change_lang("director") ?><br><br>

                        <b><?= change_lang("org-struct-page-deputy-director-tag") ?></b><br><?= change_lang("deputy-director") ?><br><br>

                        <b><?= change_lang("org-struct-page-executive-secretary-tag") ?></b><br><?= change_lang("executive-secretary") ?><br><br>

                        <b><?= change_lang("org-struct-page-board-tag") ?><br> </b><?= change_lang("board-composed") ?><br>
                        <?= change_lang("board-member1") ?><br>
                        <?= change_lang("board-member2") ?><br>
                        <?= change_lang("board-member3") ?><br>
                        <?= change_lang("board-member4") ?><br>
                        <?= change_lang("board-member5") ?><br><br>

                        <b><?= change_lang("org-struct-page-scinetific-conucil-tag") ?><br> </b><?= change_lang("all-integrated-members") ?><br><br>

                        <b><?= change_lang("org-struct-page-advisory-council-tag") ?><br></b>
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