<?php
include 'config/dbconnection.php';
include 'models/functions.php';

//ligação à base de dados
$pdo = pdo_connect_mysql();
$language = ($_SESSION["lang"] == "en") ? "_en" : "";

$perPage = 8;
$currentPage = isset($_GET['page']) ? max((int)$_GET['page'],1) : 1;

$offset = ($currentPage - 1) * $perPage;

$query = "SELECT id, email, nome,
        COALESCE(NULLIF(sobre{$language}, ''), sobre) AS sobre,
        COALESCE(NULLIF(areasdeinteresse{$language}, ''), areasdeinteresse) AS areasdeinteresse,
        ciencia_id, tipo, fotografia, orcid, scholar, research_gate, scopus_id
        FROM investigadores WHERE tipo = \"Integrado\" ORDER BY nome
        LIMIT :limit OFFSET :offset";



//Consulta
$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit',$perPage,PDO::PARAM_INT);
$stmt->bindValue(':offset',$offset,PDO::PARAM_INT);
$stmt->execute();

//Resultado da nossa query
$investigadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryCount = "SELECT COUNT(*) FROM investigadores WHERE tipo = 'Integrado'";
$totalRows = $pdo->query($queryCount)->fetchColumn();
$totalPages = ceil($totalRows/$perPage);
?>

<!DOCTYPE html>
<html>

      <?=template_header('Integrados/as');?>
      
      <!-- product section -->
      <section class="product_section layout_padding">
      <div style="background-color: #dbdee1; padding-top: 50px; padding-bottom: 50px;">
         <div class="container">
            <div class="heading_container3">
               
                  <h3 style="margin-bottom: 5px;">
                     <?= change_lang("integrated-researchers-page-heading") ?>
                  </h3>
                 
                     <h5 class="heading2_h5">
                          <?= change_lang("integrated-researchers-page-heading-desc") ?>
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

         <?php foreach ($investigadores as $investigador): ?>

               <div  class="ml-5 imgList">
                  <a href="integrado.php?integrado=<?=$investigador['id']?>">
                        <div  class="image_default">
                        <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="../backoffice/assets/investigadores/<?=$investigador['fotografia']?>" alt="">
                           <div class="imgText justify-content-center m-auto"><?=$investigador['nome']?></div>
                        </div>
                  </a> 
               </div>

         <?php endforeach; ?>
      </div>

      <div class="pagination justify-content-center">
            <?php
            $adjacents = 3; // Quantidade de páginas adjacentes a serem mostradas
            $startPage = max(1, $currentPage - $adjacents); // A primeira página visível
            $endPage = min($totalPages, $currentPage + $adjacents); // A última página visível

            // Exibir a página anterior
            if ($currentPage > 1) {
               echo '<a href="?page=' . ($currentPage - 1) . '" class="page-item"><span class="page-link">&laquo;</span></a>';
            }

            // Exibir as páginas antes da página atual
            for ($i = $startPage; $i < $currentPage; $i++) {
               echo '<a href="?page=' . $i . '" class="page-item"><span class="page-link">' . $i . '</span></a>';
            }

            // Exibir a página atual
            echo '<a href="?page=' . $currentPage . '" class="page-item active"><span class="page-link">' . $currentPage . '</span></a>';

            // Exibir as páginas depois da página atual
            for ($i = $currentPage + 1; $i <= $endPage; $i++) {
               echo '<a href="?page=' . $i . '" class="page-item"><span class="page-link">' . $i . '</span></a>';
            }

            // Exibir a próxima página
            if ($currentPage < $totalPages) {
               echo '<a href="?page=' . ($currentPage + 1) . '" class="page-item"><span class="page-link">&raquo;</span></a>';
            }
            ?>
         </div>        
<!--             <div class="row justify-content-center mt-3">
               
               <div  class="ml-4 imgList">
               
                  <div  class="image_default">
                  <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/joana-bento-rodrigues.jpg" alt="">
                     <div class="imgText justify-content-center m-auto">teresa silva</div>
                  </div>  
               
               </div>

               <div class="ml-4 imgList">

                  <div  class="image_default">
                  <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/maisum.jpg" alt="">
                     <div class="imgText justify-content-center m-auto">josé constâncio</div>
                  </div>

               </div>

               <div class="ml-4 imgList">
               
                  <div class="image_default">
                  <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/pexels-photo-2272853.jpeg" alt="">
                     <div class="imgText justify-content-center m-auto">josefa vasconcelos</div>
                  </div>


               </div>
   
            </div>


            <div class="row justify-content-center mt-3">
               
               <div  class="ml-4 imgList">
               
                  <div  class="image_default">
                  <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/whatsapp-image-2021.jpg" alt="">
                     <div class="imgText justify-content-center m-auto">ana maria simões</div>
                  </div>  
               
               </div>

               <div class="ml-4 imgList">

                  <div  class="image_default">
                  <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/55918.jpg" alt="">
                     <div class="imgText justify-content-center m-auto">maria bettencourt</div>
                  </div>

               </div>

               <div class="ml-4 imgList">
               
                  <div class="image_default">
                  <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/5591801.jpg" alt="">
                     <div class="imgText justify-content-center m-auto">cristina marques</div>
                  </div>


               </div>
            
            </div> -->

                
         </div>

      </div>
   </section>

      <!-- end product section -->

      <?=template_footer();?>

   </body>
</html>