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
$sql = "SELECT id, nome_arquivo, caminho, pasta, nome_documento, nome_titulo FROM documentos_frontoffice WHERE id = ?";
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

// Buscar títulos existentes para o select
$sqlTitulos = "SELECT DISTINCT nome_titulo FROM documentos_frontoffice WHERE nome_titulo IS NOT NULL AND nome_titulo != '' ORDER BY nome_titulo";
$resultTitulos = $conn->query($sqlTitulos);

$titulosExistentes = [];
while ($rowTitulo = $resultTitulos->fetch_assoc()) {
    $titulosExistentes[] = $rowTitulo['nome_titulo'];
}

// Processar o formulário de edição
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_documento = $_POST["nome_documento"] ?? "";
    $nome_titulo = $_POST["nome_titulo"] ?? "";

    $sql = "UPDATE documentos_frontoffice SET nome_documento = ?, nome_titulo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome_documento, $nome_titulo, $id);
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
            <form action="edit.php?id=<?= $documento['id'] ?>" method="post" onsubmit="return syncTitulo();">
                <div class="form-group">
                    <label for="nome_arquivo">Nome do Arquivo:</label>
                    <input type="text" id="nome_arquivo" class="form-control" value="<?= htmlspecialchars($documento['nome_arquivo']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Nome do Documento:</label>
                    <input type="text" name="nome_documento" class="form-control" value="<?= htmlspecialchars($documento['nome_documento']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Título do Documento:</label>
                    <input type="text" id="nome_titulo" name="nome_titulo" class="form-control" value="<?= htmlspecialchars($documento['nome_titulo']) ?>" required>

                    
                </div>

               

                <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
                <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Sincroniza o valor para o input hidden antes do submit
    function syncTitulo() {
        const select = document.getElementById('titulo-select');
        const texto = document.getElementById('titulo-text');
        const hidden = document.getElementById('nome_titulo_hidden');

        if (select.value) {
            hidden.value = select.value;
        } else if (texto.value.trim() !== "") {
            hidden.value = texto.value.trim();
        } else {
            alert("Por favor, selecione ou digite o título do documento.");
            return false;
        }
        return true;
    }

    // Quando selecionar algo no select, atualizar o campo texto para sincronizar visualmente
    function onSelectChange() {
        const select = document.getElementById('titulo-select');
        const texto = document.getElementById('titulo-text');
        texto.value = select.value;
    }

    // Se o utilizador digitar no input texto, limpa seleção do select para evitar conflito
    document.getElementById('titulo-text').addEventListener('input', function() {
        if(this.value.length > 0){
            document.getElementById('titulo-select').value = "";
        }
    });

    // Inicializa o campo hidden ao carregar a página
    window.onload = () => {
        syncTitulo();
    }
</script>
