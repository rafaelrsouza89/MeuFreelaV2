<?php
session_start();
require_once 'includes/db.php';
try {
    $sql = "SELECT v.id, v.titulo, v.local, u.nome AS nome_contratante FROM vaga AS v JOIN usuario AS u ON v.id_usuario = u.id WHERE v.data_limite >= CURDATE() ORDER BY v.data_publicacao DESC";
    $vagas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) { $error_message = "Erro ao buscar vagas."; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Disponíveis - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

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

    <main class="container py-5">
        <h1 class="mb-4">Vagas Disponíveis</h1>
        <?php if (!empty($vagas)): ?>
            <?php foreach ($vagas as $vaga): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($vaga['titulo']); ?></h5>
                                <p class="card-text mb-1"><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                                <p class="card-text text-muted"><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                            </div>
                            <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Nenhuma vaga disponível no momento.
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container"><p class="mb-0">MeuFreela &copy; 2025</p></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>