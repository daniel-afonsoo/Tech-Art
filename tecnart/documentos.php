<?php
include 'config/dbconnection.php';
include 'models/functions.php';
$pdo = pdo_connect_mysql();

registar_visita($pdo, "documentos.php");

$language = ($_SESSION["lang"] == "en") ? "_en" : "_pt";


$stmt = $pdo->prepare("SELECT * FROM documentos_frontoffice ORDER BY nome_titulo_pt, nome_documento_pt, nome_titulo_en, nome_documento_en");
$stmt->execute();
$documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organizar documentos por título
$documentos_por_titulo = [];
foreach($documentos as $documento) {
    $titulo = $documento['nome_titulo' . $language] ?? 'Sem Título';
    if (!isset($documentos_por_titulo[$titulo])) {
        $documentos_por_titulo[$titulo] = [];
    }
    $documentos_por_titulo[$titulo][] = $documento;
}
?>

<?= redirectPageLanguage("documentos.php","documents.php"); ?>

<!DOCTYPE html>
<html>
    <?= template_header('Regulamentos'); ?>

    <body style="min-height:100vh;display:flex;flex-direction:column;">
    <div style="flex:1;">
        <section class="product_section layout_padding">
            <div style="padding-top: 50px; padding-bottom: 30px;">
                <div class="container">
                    <div class="heading_container3">
                        <div style="margin-top: 25px;">
                        <?php foreach($documentos_por_titulo as $titulo => $docs): ?>
                            <div style="margin-top: 30px;">
                                <!-- Título da categoria -->
                                <h4 style="color: #333; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #002169; padding-bottom: 5px;">
                                    <?= htmlspecialchars($titulo) ?>
                                </h4>
                                
                                <!-- Documentos dessa categoria -->
                                <?php foreach($docs as $documento): ?>
                                    <div style="margin-left: 20px; margin-bottom: 10px;">
                                        &bull;
                                        <a href="../backoffice/documentos_frontoffice/<?= htmlspecialchars($documento['caminho']) ?>" 
                                           target="_blank" 
                                           style="font-size:1.1em; text-decoration: none; color:rgb(46, 111, 249);">
                                            <?= htmlspecialchars($documento['nome_documento' . $language] ?? $documento['nome_arquivo']) ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($documentos_por_titulo)): ?>
                            <div style="margin-top: 20px; color: #666; font-style: italic;">
                                Nenhum documento público encontrado.
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?= template_footer(); ?>
    </body>
</html>