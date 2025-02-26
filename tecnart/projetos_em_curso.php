<?php
include 'config/dbconnection.php';
include 'models/functions.php';

//ligação à base de dados
$pdo = pdo_connect_mysql();
$language = ($_SESSION["lang"] == "en") ? "_en" : "";

$perPage = 8;


// Determina a página atual com base no parâmetro 'page' no URL
$currentPage = isset($_GET['page']) ? max(1, $_GET['page']) : 1;

//Termo da pesquisa no URL, se existir, senão fica como uma string vazia
$searchTerm = isset($_GET['query']) ? trim($_GET['query']) : '';

//A partir de qual projeto deve ser mostrado (só mostra apartir do número que estiver armazenado no offset)
$offset = ($currentPage - 1) * $perPage;

$whereClause ="concluido= :estado";

// Se houver um termo de pesquisa, adiciona uma condição para encontrar nomes similares
if(!empty($searchTerm)){
   $whereClause .= " AND nome LIKE :search";
}

//A query principal
$query = "SELECT id,COALESCE(NULLIF(nome{$language}, ''), nome) AS nome,fotografia FROM projetos WHERE $whereClause
LIMIT :limit OFFSET :offset";

//query para contar o número total de resultados
$queryCount = "SELECT COUNT(*) FROM projetos WHERE $whereClause";


//Prepara a query principal 
$stmt = $pdo->prepare($query);
$stmt->bindValue(':estado', false, PDO::PARAM_STR);

if (!empty($searchTerm)) {
   $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}

// Define os valores do limit e do offset para a paginação
$stmt->bindValue(':limit',$perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset',$offset, PDO::PARAM_INT);

// Executa a query e armazena os resultados

$stmt->execute();

//Resultado da nossa query
$projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Prepara a query para contar o número total de resultados
$stmtCount = $pdo->prepare($queryCount);
$stmtCount->bindValue(':estado',false, PDO::PARAM_STR);
if (!empty($searchTerm)) {
    $stmtCount->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}

// Executa a count query e obtém o total de resultados
$stmtCount->execute();
$totalRows = $stmtCount->fetchColumn();


//Número total de páginas que precisamos para dividir todos os projetos (8 por página).
$totalPages = ceil($totalRows/$perPage);


?>

<!DOCTYPE html>
<html>

<?= template_header(change_lang("projects-ongoing-page-heading")); ?>

<!-- product section -->
<section class="product_section layout_padding">
   <div style="background-color: #dbdee1; padding-top: 50px; padding-bottom: 50px;">
      <div class="container">
         <div class="heading_container3">

            <h3 style="margin-bottom: 5px;">
               <?= change_lang("projects-ongoing-page-heading") ?>
            </h3>

            <h5 class="heading2_h5">
               <?= change_lang("projects-ongoing-page-description") ?>
            </h5>

         </div>
      </div>
   </div>
</section>
<!-- end product section -->

<section class="product_section layout_padding">
<div class="row mt-5">
  <div class="col-md-5 mx-auto">
    <form class="d-flex" action="" method="GET">
      <div class="input-group">
        <input
          class="example-search-input form-control"
          type="search"
          value="Pesquisar"
          id="example-search-input"
          name="query"
          placeholder="Pesquisar"
          onfocus="this.value=''"
        />
        <button
          class="example-search-button btn"
          type="submit"
        >
          <i class="fa fa-search"></i>
        </button>
      </div>
    </form>
  </div>
</div>
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

               echo '<a href="?page=' . ($currentPage - 1) . '&query=' . urlencode($searchTerm) . '" class="page-item"><span class="page-link">&laquo;</span></a>';

            }

            // Exibir as páginas antes da página atual
            for ($i = $startPage; $i < $currentPage; $i++) {

               echo '<a href="?page=' . $i . '&query=' . urlencode($searchTerm) . '" class="page-item"><span class="page-link">' . $i . '</span></a>';
            }

            // Exibir a página atual
            echo '<a href="?page=' . $currentPage . '&query=' . urlencode($searchTerm) . '" class="page-item active"><span class="page-link">' . $currentPage . '</span></a>';

            // Exibir as páginas depois da página atual
            for ($i = $currentPage + 1; $i <= $endPage; $i++) {
               echo '<a href="?page=' . $i . '&query=' . urlencode($searchTerm) . '" class="page-item"><span class="page-link">' . $i . '</span></a>';

            }

            // Exibir a próxima página
            if ($currentPage < $totalPages) {

               echo '<a href="?page=' . ($currentPage + 1) . '&query=' . urlencode($searchTerm) . '" class="page-item"><span class="page-link">&raquo;</span></a>';
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