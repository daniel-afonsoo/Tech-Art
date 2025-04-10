<?php
require "../verifica.php";
require "../config/basedados.php";

// Verificação de administrador
if ($_SESSION["autenticado"] != "administrador") {
    echo "<script>alert('Apenas administradores podem editar documentos.'); window.location.href='index.php';</script>";
    exit;
}

if (!isset($_GET["id"])) {
    echo "<script>alert('ID do documento não especificado.'); window.location.href='index.php';</script>";
    exit;
}

$id = intval($_GET["id"]);
$mensagem = "";

// Atualizar permissões
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $permissoesSelecionadas = isset($_POST["permissoes"]) ? $_POST["permissoes"] : [];
    $permissoesStr = implode(",", $permissoesSelecionadas);

    $sql = "UPDATE documentos SET permissoes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $permissoesStr, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo "<script>alert('Permissões atualizadas com sucesso!'); window.location.href='index.php';</script>";
        exit;
    } else {
        $mensagem = "Erro ao atualizar permissões.";
    }
    
    $stmt->close();
}

// Buscar documento
$sql = "SELECT nome_arquivo, permissoes FROM documentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$documento = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Array de permissões já definidas
$permissoesAtuais = explode(",", $documento["permissoes"]);
?>

<!-- HTML -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<div class="container mt-5">
    <h3>✏️ Editar Documento</h3>

    <?php if (!empty($mensagem)) { ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php } ?>

    <form method="POST">
        <div class="form-group">
            <label>Nome do Documento:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($documento['nome_arquivo']) ?>" disabled>
        </div>

        <div class="form-group">
            <label>Permissões de Visibilidade:</label><br>

            <?php
            $grupos = ["todos", "Integrado", "Colaborador", "Aluno", "Externo"];
            foreach ($grupos as $grupo) {
                $checked = in_array($grupo, $permissoesAtuais) ? "checked" : "";
                echo '<div class="form-check">';
                echo "<input type='checkbox' name='permissoes[]' value='$grupo' class='form-check-input' $checked>";
                echo "<label class='form-check-label'>$grupo</label>";
                echo "</div>";
            }
            ?>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
