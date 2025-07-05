<?php
require "../config/basedados.php";
require "../verifica.php";

// 1) Preparar permissões
$tipoUtilizador   = $_SESSION["autenticado"] ?? null;
$mapaPermissoes   = [
    'administrador' => 'Todos',
    'integrado'     => 'Integrado',
    'colaborador'   => 'Colaborador',
    'aluno'         => 'Aluno',
    'externo'       => 'Externo',
];

// 2) Buscar todos os documentos
$sql    = "SELECT * FROM documentos_backoffice ORDER BY nome_arquivo";
$result = $conn->query($sql);

$documentosPermitidos = [];

if ($result && $result->num_rows > 0) {
    while ($doc = $result->fetch_assoc()) {
        // 2.1) Verifica permissão
        $perms = array_map('trim', explode(',', $doc['permissoes']));
        if (
            $tipoUtilizador !== 'administrador' &&
            !in_array('Todos', $perms) &&
            (!isset($mapaPermissoes[$tipoUtilizador]) ||
             !in_array($mapaPermissoes[$tipoUtilizador], $perms))
        ) {
            continue;
        }

        // 2.2) Filtrar só ficheiros PT ou EN
        $prefix = strtoupper(substr($doc['nome_arquivo'], 0, 2));
        if (!in_array($prefix, ['PT', 'EN'])) {
            continue;
        }

        $documentosPermitidos[] = $doc;
    }
}

$conn->close();
?>

<!-- HTML -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h3>📂 Documentos do BackOffice</h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered mb-4">
        <thead class="thead-light">
          <tr>
            <th>Documento</th>
            <th>Português</th>
            <th>Inglês</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php if (count($documentosPermitidos)): ?>
          <?php foreach ($documentosPermitidos as $doc): 
            $prefix = strtoupper(substr($doc['nome_arquivo'], 0, 2));
          ?>
          <tr>
            <td><?= htmlspecialchars($doc['nome_arquivo']) ?></td>
            <td>
              <?php if ($prefix === 'PT'): ?>
                <a href="<?= htmlspecialchars($doc['caminho']) ?>" target="_blank">File</a>
              <?php else: ?>
                &ndash;
              <?php endif; ?>
            </td>
            <td>
              <?php if ($prefix === 'EN'): ?>
                <a href="<?= htmlspecialchars($doc['caminho']) ?>" target="_blank">File</a>
              <?php else: ?>
                &ndash;
              <?php endif; ?>
            </td>
            <td>
              <?php if ($tipoUtilizador === 'administrador'): ?>
                <a href="remover.php?id=<?= $doc['id'] ?>" 
                   class="btn btn-sm btn-danger" 
                   onclick="return confirm('Tem certeza que deseja remover este documento?')">
                  Remover
                </a>
                <a href="edit.php?id=<?= $doc['id'] ?>" 
                   class="btn btn-sm btn-primary ml-2">
                  Editar
                </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center">Nenhum documento disponível para você.</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-end">
        <a href="upload.php" class="btn btn-success">
          <i class="fa fa-plus"></i> Adicionar Novo Documento
        </a>
      </div>
    </div>
  </div>
</div>
