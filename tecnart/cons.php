<?php

include 'config/dbconnection.php';
include 'models/functions.php';


$pdo = pdo_connect_mysql();

// Verifica a linguagem atual
$language = ($_SESSION["lang"] == "en") ? "_en" : "";

// Obtém o ID do membro do conselho consultivo a partir da URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query para ir buscar os dados do membo específico
$query = "SELECT id, nome, fotografia, 
          COALESCE(NULLIF(sobre{$language}, ''), sobre) AS sobre 
          FROM conselho_consultivo WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$member) {
    exit('Membro não encontrado!');
}

?>

<?= template_header($member['nome']); ?>

<!-- product section -->
<section>
    <div class="totall">
        <div class="barraesquerda">
            <h3 class="heading_h3" style="font-size: 38px; margin-bottom: 20px; padding-top: 60px; padding-right: 10px; padding-left: 60px; word-wrap: break-word;">
                <?= $member['nome'] ?>
            </h3>
        </div>
        <div id="resto" class="infoCorpo">
            <img style="object-fit: cover; width:255px; height:310px; padding-left: 50px; padding-top: 50px" src="./assets/images/<?= $member['fotografia'] ?>" alt="<?= $member['nome'] ?>">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-right: 10px; padding-left: 50px;">
                <?= change_lang("about-tab-title-class") ?>
            </h3>

            <div class="textInfo" style="padding-bottom: 80px;">
                <?= $member['sobre'] ?>
            </div>
        </div>
    </div>
</section>
<!-- end product section -->

<?= template_footer(); ?>

</body>

</html>