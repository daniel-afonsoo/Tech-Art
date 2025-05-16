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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["arquivo"])) {
    $pasta = isset($_POST['nova_pasta']) && !empty($_POST['nova_pasta']) ? $_POST['nova_pasta'] : $_POST['pasta_existente'];
    $dirDestino = $uploadsDir . $pasta . "/";

    // Criar a pasta se não existir
    if (!file_exists($dirDestino)) {
        mkdir($dirDestino, 0777, true);
    }

    $nomeArquivo = $_FILES["arquivo"]["name"];
    $caminhoCompleto = $dirDestino . $nomeArquivo;

    // Verificar se o ficheiro já existe na pasta
    $contador = 1;
    $info = pathinfo($nomeArquivo);
    $extensao = isset($info['extension']) ? "." . $info['extension'] : "";

   while (file_exists($caminhoCompleto)) {
    $novoNome = $info['filename'] . "_v" . $contador . $extensao;
    $caminhoCompleto = $dirDestino . $novoNome;
    $contador++;
  }

$nomeArquivo = basename($caminhoCompleto);
    // Permissões da pasta
    $permissoes = isset($_POST['permissoes']) ? implode(",", $_POST['permissoes']) : "privado";

    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
        // Salvar na base de dados
        $sql = "INSERT INTO documentos (nome_arquivo, caminho, pasta, permissoes) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nomeArquivo, $caminhoCompleto, $pasta, $permissoes);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Arquivo enviado com sucesso!'); window.location.href='index.php';</script>";
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
                    <input type="file" name="arquivo" class="form-control" required>
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
                    <input type="checkbox" name="permissoes[]" value="Integrado" class="form-check-input"> Investigadores/as Integrados/as<br>
                    <input type="checkbox" name="permissoes[]" value="Colaborador" class="form-check-input"> Colaboradores/as<br>
                    <input type="checkbox" name="permissoes[]" value="Aluno" class="form-check-input"> Alunos<br>
                    <input type="checkbox" name="permissoes[]" value="Externo" class="form-check-input"> Externos<br>
                </div>

                <button type="submit" class="btn btn-success mt-3">Enviar</button>
                <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
            </form>
        </div>
    </div>
</div>
