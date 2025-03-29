<?php
require "../verifica.php";
require "../config/basedados.php";

$find = "";



$search = isset($_GET['search']) ? $_GET['search'] : '';
$perPage = 10;
$searchName =  '%' . $search . '%';


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;





$sql = "SELECT id, nome, email, sobre, fotografia FROM conselho_consultivo WHERE nome LIKE '$searchName';";


$result = mysqli_query($conn, $sql);



$totalSql = "SELECT COUNT(*) FROM conselho_consultivo WHERE nome LIKE '$searchName'";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_row($totalResult)[0];
$totalPages = ceil($totalRows / $perPage);



//Alterações efetuadas até aqui 




if (isset($_POST["anoRelatorio"])) {
	$_SESSION["anoRelatorio"] = $_POST["anoRelatorio"];
}

?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</link>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="../assets/js/citation-js-0.6.8.js"></script>

<style type="text/css">
	<?php
	$css = file_get_contents('../styleBackoffices.css');
	echo $css;
	?>
</style>
<?php
if (@$_SESSION["anoRelatorio"] != "") {
	$anoAtual = $_SESSION["anoRelatorio"];
} else {
	$anoAtual = date("Y");
}
?>

<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>Conselho Consultivo</h2>
					</div>
                    
					
					
				    <div class="col text-center " style="margin-left: -150px;">

                          <form method="GET" action="">
                               <div class="input-group" style="margin-left:-80px;"  >

                    
                                     <input type="text" name="search" class="form-control" placeholder="Pesquisar">
            
                     <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="submit">
                            <i class="material-icons">search</i>
                          </button>
                          </div>
                     </div>
                           </form>
                     </div>

                 <!--Alterações efetuadas aqui -->
				 <?php if ($_SESSION["autenticado"] == 'administrador') { ?>
					<!-- passei de "col-sm-6" para "col-auto" , o que fará com que o botão ocupe apenas o espaço necessário   -->
						<div class="col-auto">
							<a href="create.php" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Adicionar Novo Perfil</span></a>
						</div>
					<?php } ?>


				


				</div>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						
						<th>Nome</th>
						<th>Email</th>
						<th>Fotografia</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							if ($_SESSION["autenticado"] == 'administrador' || $_SESSION["autenticado"] == $row["id"]) {
								echo "<tr>";
								echo "<td>" . $row["nome"] . "</td>";
								echo "<td>" . $row["email"] . "</td>";
								echo "<td><img src='../assets/investigadores/$row[fotografia]' width = '100px' height = '100px'></td>";
								echo "<td style='min-width:250px;'><a href='edit.php?id=" . $row["id"] . "' class='w-100 mb-1 btn btn-primary'><span>Alterar Perfil</span></a>";
								if ($_SESSION["autenticado"] == 'administrador') {
									echo "<a href='remove.php?id=" . $row["id"] . "' class='w-100 mb-1 btn btn-danger'><span>Apagar</span></a><br>";
								}
		
				
								echo "</td>";
								echo "</tr>";
							}
						}
					}
					?>
				</tbody>
			</table>
        <!-- Paginação -->
			<div class="pagination">

                    <!-- Botão "Anterior" -->
					<!--Verificar se $page (número da página atual) é maior que 1.
                        Se for maior, exibe o botão "Anterior".
                        Se a página atual for a primeira (1), não exibe o botão.-->
                    <?php if ($page > 1): ?>
                        <a href="?search=<?= $search ?>&page=<?= $page - 1 ?>" class="btn btn-secondary">Anterior</a>
                    <?php endif; ?>

                    
					<!-- Exibir os botões numerados de página apenas se o número total de projetos ($totalRows) for maior ou igual a 5-->
                     <?php if ($totalRows >= 5): ?>
						<!-- Gerar os botões numerados de 1 até....-->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                           <a href="?search=<?= $search ?>&page=<?= $i ?>" class="btn btn-light"><?= $i ?></a>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <!-- Botão "Próximo" -->
					 <!--Verifica se a página atual $page é menor que o total de páginas $totalPages.
                         Se for menor, exibe o botão "Próximo".
                         Caso contrário, o botão não é exibido.-->
                    <?php if ($page < $totalPages): ?>
                        <a href="?search=<?= $search ?>&page=<?= $page + 1 ?>" class="btn btn-secondary">Próximo</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
   
