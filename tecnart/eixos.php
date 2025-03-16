<?php
include 'models/functions.php';
?>

<!DOCTYPE html>
<html>

<?= template_header('Eixos'); ?>


<!-- product section -->
<section class="product_section layout_padding">
    <div style="padding-top: 50px; padding-bottom: 30px;">
        <div class="container">
            <div class="heading_container3">

                <h3 style="text-transform: uppercase;">
                    <?= change_lang("axes-page-heading") ?>
                </h3>

                <div class="flex-container mobile_reverse">
                    <div class="flex-left">
                        <figure class="imgfigura">
                            <img class="imgeixos w-100" style="max-width: 330px;" src="./assets/images/eixos.jpg" alt="Boat">
                            <figcaption class="imgs"></figcaption>
                        </figure>
                    </div>
                    <div class="flex-right">

                        <p><?= get_text("axes-page-p1-txt") ?></p>

                        <p class="text-uppercase"><b>a) <?= get_text("axes-page-a-txt") ?></b></p>
                        <p class="text-uppercase"><b>b) <?= get_text("axes-page-b-txt") ?></b></p>
                        <br><br>
                        <p><?= get_text("axes-page-p2-txt") ?></p>


                        <p><b>a1) </b><?= get_text("axes-page-a-one-txt") ?></p>
                        <p><b>a2) </b><?= get_text("axes-page-a-two-txt") ?></p>
                        <br>
                        <p><?= get_text("axes-page-p3-txt") ?></p>
                        <p><b>b1) </b><?= get_text("axes-page-b-one-txt") ?></p>

                        <p><b>b2) </b><?= get_text("axes-page-b-two-txt") ?></p>

                        <p><?= get_text("bottom-text") ?></p>

                    </div>
                </div>               

            </div>
         </div>
      </div>
   </section>
      <!-- end product section -->
      
      <?=template_footer();?>

   </body>
</html>