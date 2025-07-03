<?php
require "../config/basedados.php";
require "../verifica.php";

// Diretoria base para uploads
$uploadsDir = "../assets/docs/";

// Criar a pasta base se não existir
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Processar o upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["arquivo"])) {
    $pasta = isset($_POST['nova_pasta']) && !empty($_POST['nova_pasta']) ? $_POST['nova_pasta'] : $_POST['pasta_existente'];
    $dirDestino = $uploadsDir . $pasta . "/";

    // Criar a pasta se não existir
    if (!file_exists($dirDestino)) {
        if (!mkdir($dirDestino, 0777, true)) {
            echo "<script>alert('Erro ao criar a pasta. Verifique as permissões do servidor.');</script>";
            exit;
        }
    }

    $nomeArquivo = basename($_FILES["arquivo"]["name"]);
    $caminhoCompleto = $dirDestino . $nomeArquivo;

    // Verificar se o arquivo já existe na pasta
    $contador = 1;
    $info = pathinfo($nomeArquivo);
    $extensao = isset($info['extension']) ? "." . $info['extension'] : "";

    while (file_exists($caminhoCompleto)) {
        $novoNome = $info['filename'] . "_v" . $contador . $extensao;
        $caminhoCompleto = $dirDestino . $novoNome;
        $contador++;
    }

    $nomeArquivo = basename($caminhoCompleto);

    // Mover o arquivo
    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
        $tamanho = filesize($caminhoCompleto);
        $permissoes = isset($_POST["permissoes"]) ? implode(",", $_POST["permissoes"]) : "todos";

        // Salvar na base de dados (sem o campo 'pasta')
        $sql = "INSERT INTO documentos_backoffice (nome_arquivo, caminho, permissoes, tamanho, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "<script>alert('Erro ao preparar a consulta: " . htmlspecialchars($conn->error) . "');</script>";
            exit;
        }

        $tipo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
        $stmt->bind_param("sssis", $nomeArquivo, $caminhoCompleto, $permissoes, $tamanho, $tipo);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Arquivo enviado com sucesso!'); window.location.href='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao mover o arquivo. Verifique as permissões do diretório.');</script>";
    }
}

$conn->close();
?>

<!-- Formulário de Upload -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css ">
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

                <!-- Definir permissões -->
                <label>Quem pode ver os arquivo</label><br>
                <div class="form-check">
                    <input type="checkbox" name="permissoes[]" value="todos" class="form-check-input"> Todos<br>
                    <input type="checkbox" name="permissoes[]" value="Integrado" class="form-check-input"> Investigadores Integrados<br>
                    <input type="checkbox" name="permissoes[]" value="Colaborador" class="form-check-input"> Colaboradores<br>
                    <input type="checkbox" name="permissoes[]" value="Aluno" class="form-check-input"> Alunos<br>
                    <input type="checkbox" name="permissoes[]" value="Externo" class="form-check-input"> Externos<br>
                </div>

                <button type="submit" class="btn btn-success mt-3">Enviar</button>
                <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
            </form>
        </div>
    </div>
</div>