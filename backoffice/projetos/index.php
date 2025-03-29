<?php
require "../verifica.php";
require "../config/basedados.php";





//"isset($_GET['search'])"->Aqui estamos a chamar a função isset() para verificar se a variável $_GET['search'] existe e não é nula.
//depois do "?" o que acontece:
//Se a condição isset($_GET['search']) for verdadeira, ou seja, se o parâmetro realmente existe, então retornamos o valor de $_GET['search'] para ser atribuído à variável $search
//Se a condição for falsa, a variável $search recebe uma string vazia
//condição ? valor_se_verdadeiro : valor_se_falso;
$search = isset($_GET['search']) ? $_GET['search'] : '';




//Alterações efetuadas a partir daqui 
$perPage = 5;

//Se $search for "projeto": A consulta SQL será: SELECT * FROM projetos WHERE nome LIKE '%projeto%';
//Isso vai retornar todos os registros onde o campo nome contém a palavra "projeto", independentemente do que venha antes ou depois dela.Exemplos de resultados:
//Projeto de melhoria , Novo Projeto , Projeto A ,etc
$searchName =  '%' . $search . '%';










//----------------------------------------------------------------------------------//
//"http://localhost/Tech-Art/backoffice/projetos/?search=projeto&page=2"
//Query String: "?search=projeto&page=2"
//$_GET['search'] vai ser "projeto"
//$_GET['page'] vai ser 2
//--------------------------------------------------------------------------------//


// O isset() é uma função do PHP que verifica se uma variável está definida e não é null. Neste caso, estamos a verificar se o parâmetro page foi passado na query string da URL. 
//Se o isset($_GET['page']) for verdadeiro (ou seja, se page foi passado na URL), a expressão (int)$_GET['page'] converte o valor de $_GET['page'] para um número inteiro. 
//Isso é importante porque o valor de $_GET['page'] vem sempre como uma string (mesmo que seja um número) e por conseguinte O (int) garante que o valor seja tratado como um número inteiro. 
//Por exemplo:
//Se a URL for ...?page=5, então $_GET['page'] será "5", e (int)$_GET['page'] torna-se no número 5.
//Se ...?page=abc, que não é um número válido, o intval() retornaria 0 (porque a conversão de uma string não numérica resulta em 0).
//Se $_GET['page'] não estiver presente na URL (ou seja, se isset($_GET['page']) for falso), o código utiliza o valor 1 como valor padrão

//Exemplo prático:
//Caso 1: O parâmetro page existe e é válido:
//URL: http://localhost/Tech-Art/backoffice/projetos/?page=3
//isset($_GET['page']) retorna true, então $page = (int)$_GET['page'] torna-se 3.


//Caso 2: O parâmetro page não existe:
//URL: http://localhost/Tech-Art/backoffice/projetos/
//isset($_GET['page']) retorna false, então $page recebe o valor 1 (página inicial).

//Caso 3: O parâmetro page é inválido (não numérico):
//URL: http://localhost/Tech-Art/backoffice/projetos/?page=abc
//isset($_GET['page']) retorna true, mas (int)$_GET['page'] converte "abc" para 0, então $page = 0.
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;



//O valor calculado em $start define o índice de início da consulta SQL quando usamos LIMIT para a paginação. 


//($page - 1): O número da página é subtraído por 1 para ajustar o cálculo. 
//Isto é necessário porque, em sistemas de paginação, as páginas começam a contar de 1 (não de 0), mas no SQL, quando utilizamos LIMIT, o índice de início começa em 0
//Para a página 1: ($page - 1) * $perPage será 0 * 5 = 0, ou seja, começará a partir do primeiro registro.
//Para a página 2: ($page - 1) * $perPage será 1 * 5 = 5, ou seja, começará do 6º registro.
//Para a página 3: ($page - 1) * $perPage será 2 * 5 = 10, ou seja, começará do 11º registro.
//Se $start = 5 e $perPage = 5, a consulta SQL será: "LIMIT 5, 5"==> Isto significa: "Começar a partir do 6º registro (índice 5) e trazer 5 registros."
$start = ($page - 1) * $perPage;



//o LIMIT garante que apenas um número específico de registros seja retornado, permitindo mostrar os resultados de forma paginada
$sql = "SELECT id, nome, referencia, areapreferencial, financiamento, fotografia, concluido 
        FROM projetos 
        WHERE nome LIKE '$searchName' 
        ORDER BY nome
		LIMIT $start, $perPage";



$result = mysqli_query($conn, $sql);


// Consulta SQL para contar o número total de projetos na base de dados que atendem ao critério de pesquisa ($searchName)
//A coluna COUNT(*) retorna sempre apenas uma linha,
$totalSql = "SELECT COUNT(*) FROM projetos WHERE nome LIKE '$searchName'";

//Executar a consulta SQL criada na linha anterior ($totalSql) na base de dados e armazenar o resultado na variável $totalResult
$totalResult = mysqli_query($conn, $totalSql);

//mysqli_fetch_row armazena o conteúdo da linha retornada pela consulta SQL anterior num array numérico
//Com [0] vou extrair o valor em concreto que esta no indice[0] do array
$totalRows = mysqli_fetch_row($totalResult)[0];

//Calcula o número total de páginas para a paginação. Divide o total de registros ($totalRows) pelo número de itens por página ($perPage) 
//e arredonda para cima (com ceil()) para garantir que, mesmo que haja uma fração, haja uma página adicional
$totalPages = ceil($totalRows / $perPage);




//Alterações efetuadas até aqui 


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
	?>
</style>

<div class="container-xl">
	<div class="container-xl">
	<div class="pagination">
		<div class="table-responsive">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-sm-6">
							<h2>Projetos</h2>
						</div>
                        
						
						
				    <!--Alterações efetuadas aqui -->

				   
                       <!-- passei de "col-sm-6" para "col text-center" o que permitiu centralizar a barra de pesquisa e ajustei a barra de pesquisa mais ao centro possivel
					  atraves do "margin-left"-->
				    <div class="col text-center " style="margin-left: -150px;">


                          <form method="GET" action="">
                          <!--aqui também usei um "margin-left" que permite à "lupinha" estar numa posição mais agradável ao interagir com a interface  -->
						  <div class="input-group" style="margin-left:-80px;"  >

                        <!-- Campo de pesquisa -->
                           <input type="text" name="search" class="form-control" placeholder="Pesquisar">
            
                     <!-- Botão com a lupa dentro da caixa de pesquisa -->
                    <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="submit">
                            <i class="material-icons">search</i>
                          </button>
                          </div>
                     </div>
                           </form>
                     </div>
                 <!--Alterações efetuadas aqui -->
				 
						<!-- passei de "col-sm-6" para "col-auto" , o que fará com que o botão ocupe apenas o espaço necessário   -->
						<div class="col-auto">
							<?php 
							if ($_SESSION["autenticado"] == 'administrador') {
							?>
							<a href="create.php" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Adicionar
									Novo Projeto</span></a>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Nome</th>
							<th>Estado</th>
							<!--                 <th>Descrição</th>
				<th>Sobre Projeto</th> -->
							<th>Referência</th>
							<th>TECHN&ART Área Preferencial</th>
							<th>Financiamento</th>
							<!--                 <th>Âmbito</th>
 -->
							<th>Fotografia</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (mysqli_num_rows($result) > 0) {
							while ($row = mysqli_fetch_assoc($result)) {
								echo "<tr>";
								echo "<td>" . $row["nome"] . "</td>";
								if($row["concluido"]){
									echo "<td>Concluído</td>";
								}else{
									echo "<td>Em Curso</td>";
								}
								/*             echo "<td style='width:250px;'>".$row["descricao"]."</td>";
							echo "<td style='width:250px;'>".$row["sobreprojeto"]."</td>";
							*/
								echo "<td>" . $row["referencia"] . "</td>";
								echo "<td>" . $row["areapreferencial"] . "</td>";
								echo "<td>" . $row["financiamento"] . "</td>";
								/*             echo "<td>".$row["ambito"]."</td>";
							 */
								echo "<td><img src='../assets/projetos/$row[fotografia]' width = '100px' height = '100px'></td>";
								$sql1 = "SELECT investigadores_id FROM investigadores_projetos WHERE projetos_id = " . $row["id"];
								$result1 = mysqli_query($conn, $sql1);
								$selected = array();
								if (mysqli_num_rows($result1) > 0) {
									while (($row1 = mysqli_fetch_assoc($result1))) {
										$selected[] = $row1['investigadores_id'];
									}
								}
								if ($_SESSION["autenticado"] == "administrador" || in_array($_SESSION["autenticado"], $selected)) {
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

			

	<?php
	mysqli_close($conn);
	?>