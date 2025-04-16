<?php
require "../verifica.php";
require "../config/basedados.php";

// VISITAS POR PÁGINA
$sql = "SELECT nome, SUM(numero_acessos) AS total_acessos FROM acessos GROUP BY nome ORDER BY total_acessos DESC;";
$result = mysqli_query($conn, $sql);
$resultados = [];
$totalVisitas = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $resultados[] = $row;
    $totalVisitas += $row['total_acessos'];
}

// VISITAS POR PAÍS
$sqlPais = "
SELECT p.nome as pais, SUM(a.numero_acessos) AS total_acessos
FROM acessos a
INNER JOIN paises p ON a.pais = p.id
GROUP BY p.nome
ORDER BY total_acessos DESC;
";
$resultPais = mysqli_query($conn, $sqlPais);
$dadosPaises = [];
while ($row = mysqli_fetch_assoc($resultPais)) {
    $dadosPaises[] = $row;
}
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
.total-visitas-card {
    text-align: center;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin-top: 20px;
}
.total-visitas-card h5 {
    margin: 0;
    font-size: 1.1rem;
    color: #555;
}
.total-visitas-card .numero {
    font-size: 2.5rem;
    font-weight: bold;
    color: #007bff;
    margin-top: 8px;
}
</style>

<div class="container-xl mt-4">
    <!-- Gráfico de visitas por página -->
    <div class="card shadow p-4 mb-4">
        <h4 class="mb-3">Número de Visitas Por Página</h4>
        <canvas id="visitasChart" height="100"></canvas>
    </div>

    <!-- Card total visitas -->
    <div class="total-visitas-card">
        <h5>Total de Visitas</h5>
        <div class="numero"><?php echo $totalVisitas; ?></div>
    </div>

    <!-- Gráfico de visitas por país -->
    <div class="card shadow p-4 mt-4">
        <h4 class="mb-3">Número de Visitas Por País</h4>
        <canvas id="paisesChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de visitas por página
const ctx = document.getElementById('visitasChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php foreach($resultados as $row) echo "'".$row['nome']."'," ?>],
        datasets: [{
            label: 'Número de Visitas Por Página',
            data: [<?php foreach($resultados as $row) echo $row['total_acessos']."," ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            barThickness: 20,
            maxBarThickness: 20,
        }]
    },
    options: {
      scales: {
        y: {
          ticks: {
            callback: function(value) {
              if (Number.isInteger(value)) {
                return value;
              }
            }
          }
        }
      }
    }
});

// Gráfico de visitas por país
const ctxPais = document.getElementById('paisesChart').getContext('2d');
const chartPais = new Chart(ctxPais, {
    type: 'bar',
    data: {
        labels: [<?php foreach($dadosPaises as $row) echo "'".$row['pais']."'," ?>],
        datasets: [{
            label: 'Número de Visitas Por País',
            data: [<?php foreach($dadosPaises as $row) echo $row['total_acessos']."," ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.7)',
            barThickness: 20,
            maxBarThickness: 20
        }]
    },
    options: {
      scales: {
        y: {
          ticks: {
            callback: function(value) {
              if (Number.isInteger(value)) {
                return value;
              }
            }
          }
        }
      }
    }
});
</script>

<?php mysqli_close($conn); ?>
