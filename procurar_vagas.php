<?php
session_start();
require_once 'includes/db.php';

// 1. CAPTURAR E LIMPAR O TERMO DE BUSCA DA URL
$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    // 2. MODIFICAR A QUERY SQL PARA INCLUIR A BUSCA
    $sql = "SELECT 
                v.id, 
                v.titulo, 
                v.local, 
                u.nome AS nome_contratante
            FROM vaga AS v
            JOIN usuario AS u ON v.id_usuario = u.id
            WHERE v.data_limite >= CURDATE()";

    $params = [];

    if (!empty($search_term)) {
        $sql .= " AND (v.titulo LIKE :search OR v.descricao LIKE :search)";
        $params[':search'] = '%' . $search_term . '%';
    }

    $sql .= " ORDER BY v.data_publicacao DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $vagas = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_message = "Erro ao buscar vagas. Tente novamente mais tarde.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Disponíveis - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header class="main-header">
         <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="nav-link me-3" href="dashboard.php">Meu Painel</a>
                        <a class="nav-link" href="logout.php">Sair</a>
                    <?php else: ?>
                        <a class="nav-link me-3" href="login.php">Entrar</a>
                        <a class="btn btn-primary rounded-pill" href="cadastro.php">Cadastrar</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <div class="job-listing-panel">
            <h1 style="text-align: left; margin-bottom: 2rem;">Vagas Disponíveis</h1>

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
                <div class="alert alert-warning" role="alert">
                    <?php
                    if (!empty($search_term)) {
                        echo "Nenhuma vaga encontrada para sua busca por: <strong>" . htmlspecialchars($search_term) . "</strong>";
                    } else {
                        echo "Nenhuma vaga disponível no momento.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-secondary">Voltar para a Página Inicial</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>