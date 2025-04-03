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
                        <?= get_titulo_missao(1) ?>
                    </h3>

                    <div class="flex-container mobile_reverse">
                        <div class="flex-left">
                            <figure class="imgfigura">
                                <img class="imgmissao w-100" style="max-width:330px;" src="./assets/images/missao.jpg" alt="Boat">
                                <figcaption class="imgs"></figcaption>
                            </figure>
                        </div>
                        <div class="flex-right">
                            <?= get_text_missao(1) ?>
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
