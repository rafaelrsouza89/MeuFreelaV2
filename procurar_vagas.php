<?php
session_start();
require_once 'includes/db.php';

// Captura os valores dos filtros da URL (se existirem)
$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filter_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

try {
    // A base da query é a mesma
    $sql = "SELECT v.id, v.titulo, v.local, u.nome AS nome_contratante FROM vaga AS v JOIN usuario AS u ON v.id_usuario = u.id WHERE v.data_limite >= CURDATE()";
    $params = [];

    // Adiciona filtros à query dinamicamente
    if (!empty($search_term)) {
        $sql .= " AND (v.titulo LIKE :search OR v.descricao LIKE :search)";
        $params[':search'] = '%' . $search_term . '%';
    }
    if (!empty($filter_tipo)) {
        $sql .= " AND v.tipo_vaga = :tipo";
        $params[':tipo'] = $filter_tipo;
    }
    if (!empty($filter_categoria)) {
        $sql .= " AND v.categoria = :categoria";
        $params[':categoria'] = $filter_categoria;
    }

    $sql .= " ORDER BY v.data_publicacao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $vagas = $stmt->fetchAll();

} catch (PDOException $e) { $error_message = "Erro ao buscar vagas."; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Vagas Disponíveis - MeuFreela</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <div class="job-listing-panel">
            <h1 class="mb-4">Encontre uma Vaga</h1>

            <form action="procurar_vagas.php" method="GET" class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="Buscar por palavra-chave..." value="<?php echo htmlspecialchars($search_term); ?>"></div>
                        <div class="col-md-3">
                            <select name="tipo" class="form-select">
                                <option value="">Tipo de Vaga (Todas)</option>
                                <option value="remunerado" <?php if($filter_tipo == 'remunerado') echo 'selected'; ?>>Remunerado</option>
                                <option value="voluntario" <?php if($filter_tipo == 'voluntario') echo 'selected'; ?>>Voluntário</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                             <select name="categoria" class="form-select">
                                <option value="">Categoria (Todas)</option>
                                <option value="Musicos" <?php if($filter_categoria == 'Musicos') echo 'selected'; ?>>Músicos</option>
                                <option value="Cozinheiros" <?php if($filter_categoria == 'Cozinheiros') echo 'selected'; ?>>Cozinheiros</option>
                                </select>
                        </div>
                        <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filtrar</button></div>
                    </div>
                </div>
            </form>

            <?php if (!empty($vagas)): ?>
                <?php foreach ($vagas as $vaga): ?>
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($vaga['titulo']); ?></h5>
                                <p class="card-text mb-1"><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                                <p class="card-text text-muted"><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                            </div>
                            <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">Nenhuma vaga encontrada com os filtros selecionados.</div>
            <?php endif; ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>