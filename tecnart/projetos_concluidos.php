<?php
include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();
$language = ($_SESSION["lang"] == "en") ? "_en" : "";


//Paginação
$perPage = 8;
$currentPage = isset($_GET['page']) ? max((int)$_GET['page'],1) : 1;

//A partir de qual projeto deve ser mostrado (só mostra apartir do número que estiver armazenado no offset)
$offset = ($currentPage - 1) * $perPage;

$query = "SELECT id,COALESCE(NULLIF(nome{$language}, ''), nome) AS nome,fotografia FROM projetos WHERE concluido=true 
        LIMIT :limit OFFSET :offset";


//Consulta
$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

//Resultado da nossa query
$projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryCount = "SELECT COUNT(*) FROM projetos WHERE concluido = 'true'";
$totalRows = $pdo->query($queryCount)->fetchColumn();

//Número total de páginas que precisamos para dividir todos os projetos (8 por página).
$totalPages = ceil($totalRows/$perPage);

?>

<!DOCTYPE html>
<html>

<?= template_header(change_lang("projects-finished-page-heading")); ?>

<!-- product section -->
<section class="product_section layout_padding">
   <div style="background-color: #dbdee1; padding-top: 50px; padding-bottom: 50px;">
      <div class="container">
         <div class="heading_container3">

            <h3 style="margin-bottom: 5px;">
               <?= change_lang("projects-finished-page-heading") ?>
            </h3>

            <h5 class="heading2_h5">
               <?= change_lang("projects-finished-page-description") ?>
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

            <?php foreach ($projetos as $projeto) : ?>

               <div class="ml-5 imgList">
                  <a href="projeto.php?projeto=<?= $projeto['id'] ?>">
                     <div class="image_default">
                        <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="../backoffice/assets/projetos/<?= $projeto['fotografia'] ?>" alt="">
                        <div class="imgText justify-content-center m-auto"><?= $projeto['nome'] ?></div>
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

      </div>

   </div>
</section>

<!-- end product section -->

<?= template_footer(); ?>

</body>

</html>