<?php
include 'models/functions.php';
?>

<!DOCTYPE html>
<html>

    <?= template_header('Missão'); ?>

    <!-- Seção de Missão -->
    <section class="product_section layout_padding">
        <div style="padding-top: 50px; padding-bottom: 30px;">
            <div class="container">
                <div class="heading_container3">
                    
                    <h3 style="text-transform: uppercase;">
                        <?= get_text_missao("mission-and-goals-page-heading") ?>
                    </h3>

                    <div class="flex-container mobile_reverse">
                        <div class="flex-left">
                            <figure class="imgfigura">
                                <img class="imgmissao w-100" style="max-width:330px;" src="./assets/images/missao.jpg" alt="Boat">
                                <figcaption class="imgs"></figcaption>
                            </figure>
                        </div>
                        <div class="flex-right">
                            <?= get_text_missao("mission-and-goals-page-point-one") ?><br><br>
                            <?= get_text_missao("mission-and-goals-page-point-two") ?><br><br>
                            <b>a) </b><?= get_text_missao("mission-and-goals-page-a-txt") ?><br><br>
                            <b>b) </b><?= get_text_missao("mission-and-goals-page-b-txt") ?><br><br>
                            <b>c) </b><?= get_text_missao("mission-and-goals-page-c-txt") ?><br><br>
                            <b>d) </b><?= get_text_missao("mission-and-goals-page-d-txt") ?><br><br>
                            <b>e) </b><?= get_text_missao("mission-and-goals-page-e-txt") ?><br><br>
                            <b>f) </b><?= get_text_missao("mission-and-goals-page-f-txt") ?><br><br>
                            <b>g) </b><?= get_text_missao("mission-and-goals-page-g-txt") ?><br><br>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Fim da Seção -->

    <?= template_footer(); ?>

</body>
</html>
