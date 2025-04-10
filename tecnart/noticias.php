<?php
include 'config/dbconnection.php';
include 'models/functions.php';

$pdo = pdo_connect_mysql();

$language = ($_SESSION["lang"] == "en") ? "_en" : "";

//Paginação
$perPage = 9;
$currentPage = isset($_GET['page']) ? max((int)$_GET['page'],1) : 1;

//A partir de qual noticia deve ser mostrado (só mostra apartir do número que estiver armazenado no offset)
$offset = ($currentPage - 1) * $perPage;

$query = "SELECT id,
        COALESCE(NULLIF(titulo{$language}, ''), titulo) AS titulo,
        COALESCE(NULLIF(conteudo{$language}, ''), conteudo) AS conteudo,
        imagem,data
        FROM noticias WHERE data<=NOW() ORDER BY DATA DESC
        LIMIT :limit OFFSET :offset";


//Consulta
$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

//Resultado da nossa query
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);


//Número total de registos
$queryCount = "SELECT COUNT(*) FROM noticias WHERE 'data<=NOW()'";
$totalRows = $pdo->query($queryCount)->fetchColumn();

//Número total de páginas que precisamos para dividir todas as noticias (8 por página).
$totalPages = ceil($totalRows/$perPage);

?>

<?= redirectPageLanguage("noticias.php","news.php"); ?>

<!DOCTYPE html>
<html>

<?= template_header('Notícias'); ?>


<section class="product_section layout_padding">
   <div style="background-color: #dbdee1; padding-top: 50px; padding-bottom: 50px;">
      <div class="container">
         <div class="heading_container3">
            <h3 style="margin-bottom: 5px;">
               <?= change_lang("news-page-heading") ?>
            </h3>
            <h5 class="heading2_h5">
               <?= change_lang("news-page-heading-desc") ?>
            </h5>

         </div>
      </div>
   </div>
</section>


<section class="product_section layout_padding">
   <div style="padding-top: 20px;">
      <div class="container">
         <div class="row justify-content-center mt-3">

            <?php foreach ($noticias as $noticia) : ?>
               <div class="ml-5 imgList">
                  <a href="noticia.php?noticia=<?= $noticia['id'] ?>">
                     <div class="image_default">
                        <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="../backoffice/assets/noticias/<?= $noticia['imagem'] ?>" alt="">
                        <div class="imgText justify-content-center m-auto" style="top:75%">
                           <?php
                           $titulo = trim($noticia['titulo']);
                           if (strlen($noticia['titulo']) > 35) {
                              $titulo = preg_split("/\s+(?=\S*+$)/", substr($noticia['titulo'], 0, 35))[0];
                           }
                           echo ($titulo !=  trim($noticia['titulo'])) ? $titulo . "..." : $titulo;
                           ?>
                        </div>
                        <h6 class="imgText m-auto" style="font-size: 11px; font-weight: 100; top:95%"><?= date("d.m.Y", strtotime($noticia['data'])) ?></h6>
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


<?= template_footer(); ?>

</body>

</html>