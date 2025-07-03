<?php
require "../verifica.php";
require "../config/basedados.php";

$uploadsDir = "uploads/";

if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

$sql = "SELECT DISTINCT pasta FROM documentos_frontoffice WHERE pasta IS NOT NULL AND pasta != '' ORDER BY pasta";
$result = $conn->query($sql);

$pastas = [];
while ($row = $result->fetch_assoc()) {
    $pastas[] = $row["pasta"];
}

$sqlTitulos = "SELECT DISTINCT nome_titulo FROM documentos_frontoffice WHERE nome_titulo IS NOT NULL AND nome_titulo != '' ORDER BY nome_titulo";
$resultTitulos = $conn->query($sqlTitulos);

$titulosExistentes = [];
while ($rowTitulo = $resultTitulos->fetch_assoc()) {
    $titulosExistentes[] = $rowTitulo['nome_titulo'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["arquivo"])) {
    $pasta = isset($_POST['nova_pasta']) && !empty($_POST['nova_pasta']) ? $_POST['nova_pasta'] : $_POST['pasta_existente'];
    $dirDestino = $uploadsDir . $pasta . "/";

    if (!file_exists($dirDestino)) {
        mkdir($dirDestino, 0777, true);
    }

    $nomeArquivo = $_FILES["arquivo"]["name"];
    $caminhoCompleto = $dirDestino . $nomeArquivo;

    $contador = 1;
    $info = pathinfo($nomeArquivo);
    $extensao = isset($info['extension']) ? "." . $info['extension'] : "";

    while (file_exists($caminhoCompleto)) {
        $novoNome = $info['filename'] . "_v" . $contador . $extensao;
        $caminhoCompleto = $dirDestino . $novoNome;
        $contador++;
    }

    $nomeArquivo = basename($caminhoCompleto);

    // Pegar os dados do formulário para nome_documento e nome_titulo
    $nome_documento = isset($_POST['nome_documento']) ? $_POST['nome_documento'] : '';
    // O título pode vir como texto livre, então pegamos do campo texto que vamos criar no JS
    $nome_titulo = isset($_POST['nome_titulo']) ? $_POST['nome_titulo'] : '';

    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
        $sql = "INSERT INTO documentos_frontoffice (nome_arquivo, caminho, pasta, nome_documento, nome_titulo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nomeArquivo, $caminhoCompleto, $pasta, $nome_documento, $nome_titulo);

        if ($stmt->execute()) {
            echo "<script>alert('Arquivo enviado com sucesso!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Erro ao salvar no banco de dados!');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Erro no upload!');</script>";
    }
}

$conn->close();
?>

<!-- Formulário de Upload -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Upload do Documento</h3>
        </div>
        <div class="card-body">
            <form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return syncTitulo();">
                <div class="form-group">
                    <label>Escolha um arquivo:</label>
                    <input type="file" name="arquivo" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Escolha uma pasta:</label>
                    <select name="pasta_existente" class="form-control">
                        <option value="">Selecione uma pasta existente</option>
                        <?php foreach ($pastas as $pasta): ?>
                            <option value="<?= htmlspecialchars($pasta) ?>"><?= htmlspecialchars($pasta) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="d-block my-2 text-center">OU</small>
                    <input type="text" name="nova_pasta" class="form-control" placeholder="Criar nova pasta">
                </div>

                <div class="form-group">
                    <label>Nome do Documento:</label>
                    <input type="text" name="nome_documento" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Título do Documento:</label>
                    <select id="titulo-select" class="form-control" onchange="onSelectChange()">
                        <option value="">-- Selecione um título existente --</option>
                        <?php foreach ($titulosExistentes as $titulo): ?>
                            <option value="<?= htmlspecialchars($titulo) ?>"><?= htmlspecialchars($titulo) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="titulo-text" class="form-control mt-2" placeholder="Ou digite um novo título">
                    <!-- campo oculto que vai enviar o valor para o servidor -->
                    <input type="hidden" name="nome_titulo" id="nome_titulo_hidden" required>
                </div>

                <button type="submit" class="btn btn-success mt-3">Enviar</button>
                <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
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

        // Se o select tiver um valor selecionado, usa ele
        if (select.value) {
            hidden.value = select.value;
        } else if (texto.value.trim() !== "") {
            hidden.value = texto.value.trim();
        } else {
            alert("Por favor, selecione ou digite o título do documento.");
            return false; // impede submit
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
</script>
