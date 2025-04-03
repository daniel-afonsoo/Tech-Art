<?php
require "../verifica.php";
require "../config/basedados.php";
// Número de registros por página
$registros_por_pagina = 5;

// Página atual (obtida da URL, padrão é 1)
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;

// Calcular o deslocamento (OFFSET)
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Obter o número total de registros
$sql_total = "SELECT COUNT(*) AS total FROM noticias";
$result_total = mysqli_query($conn, $sql_total);
$total_registros = mysqli_fetch_assoc($result_total)['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Selecionar os dados com LIMIT e OFFSET
$sql = "SELECT id, titulo, conteudo, data, imagem FROM noticias ORDER BY data DESC, titulo LIMIT $registros_por_pagina OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style type="text/css">
	<?php
	$css = file_get_contents('../styleBackoffices.css');
	echo $css;
	?>.div-textarea {
		display: block;
		padding: 5px 10px;
		border: 1px solid lightgray;
		resize: vertical;
		overflow: auto;
		resize: vertical;
		font-size: 1rem;
		font-weight: 400;
		line-height: 1.5;
		color: #495057;
	}
</style>

<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>Notícias</h2>
					</div>
					<div class="col-sm-6">
						<?php
						if ($_SESSION["autenticado"] == "administrador") {
							echo '<a href="create.php" class="btn btn-success"><i class="material-icons">&#xE147;</i>';
							echo '<span>Adicionar Nova Notícia</span></a>';
						}
						?>
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Título</th>
						<th>Conteúdo</th>
						<th>Data</th>
						<th>Imagem</th>
					</tr>
				</thead>

				<tbody>
					<?php
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							echo "<tr>";
							echo "<td style='width:250px;'>" . $row["titulo"] . "</td>";
							echo "<td style='width:500px; height:100px;'>" . "<div class='div-textarea' style='width:100%; height:100%;'>" . $row["conteudo"] . "</div>" . "</td>";
							echo "<td style='width:250px;'>" . $row["data"] . "</td>";
							echo "<td><img src='../assets/noticias/$row[imagem]' width = '100px' height = '100px'></td>";
							if ($_SESSION["autenticado"] == "administrador") {
								echo "<td><a href='edit.php?id=" . $row["id"] . "' class='btn btn-primary'><span>Alterar</span></a></td>";
								echo "<td><a href='remove.php?id=" . $row["id"] . "' class='btn btn-danger'><span>Apagar</span></a></td>";
							}
							echo "</tr>";
						}
					}
					?>
				</tbody>
			</table>
			 <!-- Paginação -->
			 <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($pagina_atual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $pagina_atual - 1; ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo ($i == $pagina_atual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagina_atual < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $pagina_atual + 1; ?>">Próxima</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

			
		</div>
	</div>
</div>

<?php
mysqli_close($conn);
?>