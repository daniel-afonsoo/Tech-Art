<?php
include 'models/functions.php';
?>


<?= redirectPageLanguage("eixos.php","axes.php"); ?>


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
                    ######////shhshshshs
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