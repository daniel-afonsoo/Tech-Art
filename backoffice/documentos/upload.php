<?php
require "../verifica.php";
require "../config/basedados.php";

$uploadsDir = "uploads/";

// Criar a pasta uploads se não existir
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Buscar pastas existentes na base de dados
$sql = "SELECT DISTINCT pasta FROM documentos";
$result = $conn->query($sql);

$pastas = [];
while ($row = $result->fetch_assoc()) {
    $pastaPath = $uploadsDir . $row["pasta"];

    // Só adiciona à lista se a pasta ainda existir no servidor
    if (is_dir($pastaPath)) {
        $pastas[] = $row["pasta"];
    } else {
        // Se a pasta não existe mais é removida da base de dados
        $deleteSql = "DELETE FROM documentos WHERE pasta = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("s", $row["pasta"]);
        $stmt->execute();
        $stmt->close();
    }
}

// Se a requisição for POST e houver um arquivo enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["arquivo_pt"])) {
    $pasta = isset($_POST['nova_pasta']) && !empty($_POST['nova_pasta']) ? $_POST['nova_pasta'] : $_POST['pasta_existente'];
    $dirDestino = $uploadsDir . $pasta . "/";

    if (!file_exists($dirDestino)) {
        mkdir($dirDestino, 0777, true);
    }

    $tipo  = isset($_POST['permissoes_tipo']) ? $_POST['permissoes_tipo'] : "privado";
    $permissoes = isset($_POST['permissoes']) ? implode(",", $_POST['permissoes']) : "privado";

    // Upload PT
    $nomeArquivoPT = $_FILES["arquivo_pt"]["name"];
    $caminhoCompletoPT = $dirDestino . $nomeArquivoPT;
    $contador = 1;
    $infoPT = pathinfo($nomeArquivoPT);
    $extensaoPT = isset($infoPT['extension']) ? "." . $infoPT['extension'] : "";
    while (file_exists($caminhoCompletoPT)) {
        $novoNomePT = $infoPT['filename'] . "_v" . $contador . $extensaoPT;
        $caminhoCompletoPT = $dirDestino . $novoNomePT;
        $contador++;
    }
    $nomeArquivoPT = basename($caminhoCompletoPT);

    // Upload EN (opcional)
    $nomeArquivoEN = "";
    $caminhoCompletoEN = "";
    if (isset($_FILES["arquivo_en"]) && $_FILES["arquivo_en"]["error"] == 0) {
        $nomeArquivoEN = $_FILES["arquivo_en"]["name"];
        $caminhoCompletoEN = $dirDestino . $nomeArquivoEN;
        $contadorEN = 1;
        $infoEN = pathinfo($nomeArquivoEN);
        $extensaoEN = isset($infoEN['extension']) ? "." . $infoEN['extension'] : "";
        while (file_exists($caminhoCompletoEN)) {
            $novoNomeEN = $infoEN['filename'] . "_v" . $contadorEN . $extensaoEN;
            $caminhoCompletoEN = $dirDestino . $novoNomeEN;
            $contadorEN++;
        }
        $nomeArquivoEN = basename($caminhoCompletoEN);
    }

    // Move os ficheiros
    $okPT = move_uploaded_file($_FILES["arquivo_pt"]["tmp_name"], $caminhoCompletoPT);
    $okEN = true;
    if ($nomeArquivoEN) {
        $okEN = move_uploaded_file($_FILES["arquivo_en"]["tmp_name"], $caminhoCompletoEN);
    }

    if ($okPT && $okEN) {
        // guardar na base de dados (adiciona os campos nome_arquivo_en e caminho_en na tabela!)
        $sql = "INSERT INTO documentos (nome_arquivo, caminho, nome_arquivo_en, caminho_en, pasta, permissoes, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nomeArquivoPT, $caminhoCompletoPT, $nomeArquivoEN, $caminhoCompletoEN, $pasta, $permissoes, $tipo);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Arquivo(s) enviado(s) com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro no upload!');</script>";
    }
}

$conn->close();
?>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Upload do Documento</h3>
        </div>
        <div class="card-body">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Escolha um arquivo:</label>
                    <input type="file" name="arquivo_pt" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Escolha o arquivo em Inglês se quiser: (Inglês, opcional):</label>
                    <input type="file" name="arquivo_en" class="form-control">
                </div>

                <!-- Criar ou selecionar pasta -->
                <div class="form-group">
                    <label>Escolha uma pasta:</label>
                    <select name="pasta_existente" class="form-control">
                        <option value="">Selecione uma pasta existente</option>
                        <?php foreach ($pastas as $pasta): ?>
                            <option value="<?= $pasta ?>"><?= $pasta ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small>OU</small>
                    <input type="text" name="nova_pasta" class="form-control mt-2" placeholder="Criar nova pasta">
                </div>

                <!-- Definir permissões -->
                <label>Quem pode ver os arquivos dessa pasta?</label><br>
                <div class="form-check">
                    <input type="checkbox" name="permissoes[]" value="todos" class="form-check-input"> Todos<br>
                    <input type="checkbox" name="permissoes[]" value="Integrado" class="form-check-input"> Investigadores Integrados<br>
                    <input type="checkbox" name="permissoes[]" value="Colaborador" class="form-check-input"> Colaboradores<br>
                    <input type="checkbox" name="permissoes[]" value="Aluno" class="form-check-input"> Alunos<br>
                    <input type="checkbox" name="permissoes[]" value="Externo" class="form-check-input"> Externos<br>
                </div>

                <!-- Definir permissões -->
                <label>Tipo</label><br>
                <div class="form-check">
                    <input type="radio" name="permissoes_tipo" value="publico" class="form-check-input"> Público<br>
                    <input type="radio" name="permissoes_tipo" value="privado" class="form-check-input"> Privado<br>

                <button type="submit" class="btn btn-success mt-3">Enviar</button>
                <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
            </form>
        </div>
    </div>
</div>
