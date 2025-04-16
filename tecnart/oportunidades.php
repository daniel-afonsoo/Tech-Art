<?php
include 'config/dbconnection.php';
include 'models/functions.php';


$pdo = pdo_connect_mysql();

registar_visita($pdo, "oportunidades.php");

$language = ($_SESSION["lang"] == "en") ? "_en" : "";

$query = "SELECT id,COALESCE(NULLIF(titulo{$language}, ''), titulo) AS titulo,visivel,imagem FROM oportunidades WHERE visivel=true ORDER BY ID DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$oportunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= redirectPageLanguage("oportunidades.php", "opportunities.php") ?>

<!DOCTYPE html>
<html>

<?= template_header(change_lang("opportunities-page-heading")); ?>

<!-- product section -->
<section class="product_section layout_padding">
   <div style="background-color: #dbdee1; padding-top: 50px; padding-bottom: 50px;">
      <div class="container">
         <div class="heading_container3">

            <h3 style="margin-bottom: 5px;">
               <?= change_lang("opportunities-page-heading") ?>
            </h3>

            <h5 class="heading2_h5">
               <?= change_lang("opportunities-page-heading-desc") ?>
            </h5>

         </div>
      </div>
   </div>
</section>
<!-- end product section -->

<section class="product_section layout_padding">
   <div style="padding-top: 20px;">
      <div class="container">
         <div class="row justify-content-center mt-3">

            <?php foreach ($oportunidades as $oportunidade) : ?>

               <div class="ml-5 imgList">
                  <a href="oportunidade.php?oportunidade=<?= $oportunidade['id'] ?>">
                     <div class="image_default">
                        <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="../backoffice/assets/oportunidades/<?= $oportunidade['imagem'] ?>" alt="">
                        <div class="imgText justify-content-center m-auto" style="top:75%">
                           <?php
                           $titulo = trim($oportunidade['titulo']);
                           if (strlen($oportunidade['titulo']) > 35) {
                              $titulo = preg_split("/\s+(?=\S*+$)/", substr($oportunidade['titulo'], 0, 35))[0];
                           }
                           echo ($titulo !=  trim($oportunidade['titulo'])) ? $titulo . "..." : $titulo;
                           ?>
                        </div>
                     </div>
                  </a>
               </div>

            <?php endforeach; ?>

         </div>
      </div>

   </div>
</section>

<!-- end product section -->

<?= template_footer(); ?>

</body>

</html>