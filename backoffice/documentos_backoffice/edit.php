<?php
require "../config/basedados.php";
require "../verifica.php";

if ($_SESSION["autenticado"] != "administrador") {
    echo "<script>alert('Apenas administradores podem editar documentos.'); window.location.href='index.php';</script>";
    exit;
}

$id = isset($_GET["id"]) ? intval($_GET["id"]) : null;

if (!$id) {
    echo "<script>alert('ID do documento não informado.'); window.location.href='index.php';</script>";
    exit;
}

// Buscar o documento pelo ID
$sql = "SELECT id, nome_arquivo, caminho, permissoes FROM documentos_backoffice WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Documento não encontrado.'); window.location.href='index.php';</script>";
    exit;
}

$documento = $result->fetch_assoc();
$stmt->close();

// Processar o formulário de edição
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $permissoes = isset($_POST["permissoes"]) ? implode(",", $_POST["permissoes"]) : "todos";

    $sql = "UPDATE documentos_backoffice SET permissoes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $permissoes, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Documento editado com sucesso!'); window.location.href='index.php';</script>";
    exit;
}

$conn->close();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css ">

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Editar Documento</h3>
        </div>
        <div class="card-body">
            <form action="edit.php?id=<?= $documento['id'] ?>" method="post">
                <div class="form-group">
                    <label for="nome">Nome do Arquivo:</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($documento['nome_arquivo']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Permissões:</label><br>
                    <div class="form-check">
                        <input type="checkbox" name="permissoes[]" value="todos" class="form-check-input" 
                            <?= strpos($documento['permissoes'], 'todos') !== false ? 'checked' : '' ?>>
                        Todos<br>
                        <input type="checkbox" name="permissoes[]" value="Integrado" class="form-check-input"
                            <?= strpos($documento['permissoes'], 'Integrado') !== false ? 'checked' : '' ?>>
                        Investigadores Integrados<br>
                        <input type="checkbox" name="permissoes[]" value="Colaborador" class="form-check-input"
                            <?= strpos($documento['permissoes'], 'Colaborador') !== false ? 'checked' : '' ?>>
                        Colaboradores<br>
                        <input type="checkbox" name="permissoes[]" value="Aluno" class="form-check-input"
                            <?= strpos($documento['permissoes'], 'Aluno') !== false ? 'checked' : '' ?>>
                        Alunos<br>
                        <input type="checkbox" name="permissoes[]" value="Externo" class="form-check-input"
                            <?= strpos($documento['permissoes'], 'Externo') !== false ? 'checked' : '' ?>>
                        Externos<br>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
                <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>
</div>