<?php
include 'models/functions.php';
?>

<!DOCTYPE html>
<html>

      <?=template_header('Regulamentos');?>


      <!-- product section -->
      <section class="product_section layout_padding">
      <div style="padding-top: 50px; padding-bottom: 30px;">
         <div class="container">
            <div class="heading_container3">
               
                <h3 style="text-transform: uppercase;">
                    <?= change_lang("regulation-option") ?>
                </h3>
                <ul>
                    <li>
                        <?php
                            echo "<a href='./assets/".change_lang("new-admissions-regulations-file")."' target='_blank'>".change_lang("regulation-option-geral")."</a>";
                        ?>
                    </li>
                    <li>
                        <?php
                            echo "<a href='./assets/".change_lang("electoral-regulations-file")."' target='_blank'>".change_lang("electoral-option-geral")."</a>";
                        ?>
                    </li>
                    <li>
                        <?php
                            echo "<a href='./assets/".change_lang("electoral-calendar-2025-file")."' target='_blank'>".change_lang("electoral-calendar-option-geral")."</a>";
                        ?>
                    </li>
                    <li>
                        <?php
                            echo "<a href='./assets/".change_lang("electoral-calendar-2025-file-rectification")."' target='_blank'>".change_lang("electoral-calendar-option-geral-rectification")."</a>";
                        ?>
                    </li>
                </ul>
            </div>
         </div>
      </div>
   </section>
      <!-- end product section -->
      
      <?=template_footer();?>

   </body>
</html>