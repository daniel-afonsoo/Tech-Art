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
$sql = "SELECT id, nome_arquivo, caminho, pasta, nome_documento_pt, nome_documento_en, nome_titulo_pt, nome_titulo_en FROM documentos_frontoffice WHERE id = ?";
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
$sqlTitulos = "SELECT DISTINCT nome_titulo_pt, nome_titulo_en FROM documentos_frontoffice WHERE nome_titulo_pt IS NOT NULL AND nome_titulo_pt != '' ORDER BY nome_titulo_pt";
$resultTitulos = $conn->query($sqlTitulos);

$titulosExistentes = [];
while ($rowTitulo = $resultTitulos->fetch_assoc()) {
    $titulosExistentes[] = [
        'pt' => $rowTitulo['nome_titulo_pt'],
        'en' => $rowTitulo['nome_titulo_en']
    ];
}

// Processar o formulário de edição
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_documento_pt = $_POST["nome_documento_pt"] ?? "";
    $nome_documento_en = $_POST["nome_documento_en"] ?? "";
    $nome_titulo_pt = $_POST["nome_titulo_pt"] ?? "";
    $nome_titulo_en = $_POST["nome_titulo_en"] ?? "";

    $sql = "UPDATE documentos_frontoffice SET nome_documento_pt = ?, nome_documento_en = ?, nome_titulo_pt = ?, nome_titulo_en = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome_documento_pt, $nome_documento_en, $nome_titulo_pt, $nome_titulo_en, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Documento editado com sucesso!'); window.location.href='index.php';</script>";
    exit;
}

$conn->close();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome do Documento (PT):</label>
                            <input type="text" name="nome_documento_pt" class="form-control" value="<?= htmlspecialchars($documento['nome_documento_pt']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome do Documento (EN):</label>
                            <input type="text" name="nome_documento_en" class="form-control" value="<?= htmlspecialchars($documento['nome_documento_en']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Título do Documento (PT):</label>
                            <select id="titulo-select-pt" class="form-control" onchange="onSelectChange('pt')">
                                <option value="">-- Selecione um título existente --</option>
                                <?php foreach ($titulosExistentes as $titulo): ?>
                                    <option value="<?= htmlspecialchars($titulo['pt']) ?>" data-en="<?= htmlspecialchars($titulo['en']) ?>" <?= ($titulo['pt'] == $documento['nome_titulo_pt']) ? 'selected' : '' ?>><?= htmlspecialchars($titulo['pt']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" id="titulo-text-pt" class="form-control mt-2" placeholder="Ou digite um novo título" value="<?= htmlspecialchars($documento['nome_titulo_pt']) ?>">
                            <input type="hidden" name="nome_titulo_pt" id="nome_titulo_pt_hidden" value="<?= htmlspecialchars($documento['nome_titulo_pt']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Título do Documento (EN):</label>
                            <input type="text" id="titulo-text-en" name="nome_titulo_en" class="form-control" value="<?= htmlspecialchars($documento['nome_titulo_en']) ?>" placeholder="Digite o título em inglês" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Guardar Alterações</button>
                <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Sincroniza o valor para o input hidden antes do submit
    function syncTitulo() {
        const select = document.getElementById('titulo-select-pt');
        const texto = document.getElementById('titulo-text-pt');
        const hidden = document.getElementById('nome_titulo_pt_hidden');

        if (select.value) {
            hidden.value = select.value;
        } else if (texto.value.trim() !== "") {
            hidden.value = texto.value.trim();
        } else {
            alert("Por favor, selecione ou digite o título do documento em português.");
            return false;
        }
        return true;
    }

    // Quando selecionar algo no select, atualizar o campo texto para sincronizar visualmente
    function onSelectChange(idioma) {
        const select = document.getElementById('titulo-select-' + idioma);
        const texto = document.getElementById('titulo-text-' + idioma);
        const textoEn = document.getElementById('titulo-text-en');
        
        texto.value = select.value;
        
        // Se selecionou um título em português, preencher automaticamente a tradução em inglês
        if (idioma === 'pt' && select.value) {
            const selectedOption = select.options[select.selectedIndex];
            const traducaoEn = selectedOption.getAttribute('data-en');
            if (traducaoEn) {
                textoEn.value = traducaoEn;
            }
        }
    }

    // Se o utilizador digitar no input texto, limpa seleção do select para evitar conflito
    document.getElementById('titulo-text-pt').addEventListener('input', function() {
        if(this.value.length > 0){
            document.getElementById('titulo-select-pt').value = "";
        }
    });

    // Se o utilizador digitar no input texto EN, limpa o campo PT para evitar conflito
    document.getElementById('titulo-text-en').addEventListener('input', function() {
        if(this.value.length > 0){
            document.getElementById('titulo-select-pt').value = "";
            document.getElementById('titulo-text-pt').value = "";
        }
    });

    // Inicializa o campo hidden ao carregar a página
    window.onload = () => {
        syncTitulo();
    }
</script>