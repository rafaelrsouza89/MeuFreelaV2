<?php
session_start();
require_once 'includes/db.php';

$vagas_recentes = [];
try {
    // Query para buscar as 4 vagas mais recentes e ativas
    $sql = "SELECT 
                v.id, 
                v.titulo, 
                v.local, 
                u.nome AS nome_contratante
            FROM vaga AS v
            JOIN usuario AS u ON v.id_usuario = u.id
            WHERE v.data_limite >= CURDATE()
            ORDER BY v.data_publicacao DESC
            LIMIT 4";

    $stmt = $pdo->query($sql);
    $vagas_recentes = $stmt->fetchAll();

} catch (PDOException $e) {
    // Em caso de erro, a página continua funcionando, mas sem as vagas
    // error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuFreela - Encontre Vagas na sua Região</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
            <div class="d-flex">
                <a class="nav-link me-3" href="procurar_vagas.php">Vagas</a>
                <a class="nav-link me-3" href="login.php">Entrar</a>
                <a class="btn btn-primary rounded-pill" href="cadastro.php">Cadastrar</a>
            </div>
        </div>
    </nav>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Encontre a vaga de freelancer ideal</h1>
            <p class="lead">Oportunidades em Jaraguá do Sul e região</p>
            <form action="procurar_vagas.php" method="GET" class="mt-4">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control" placeholder="Digite o cargo ou palavra-chave">
                    <button class="btn btn-dark" type="submit">Buscar Vagas</button>
                </div>
            </form>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Vagas Recentes</h2>

            <?php if (!empty($vagas_recentes)): ?>
                <div class="row">
                    <?php foreach ($vagas_recentes as $vaga): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($vaga['titulo']); ?></h5>
                                    <p class="card-text mb-1"><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                                    <p class="card-text text-muted"><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                                    <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn btn-primary mt-auto">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="procurar_vagas.php" class="btn btn-outline-primary">Ver Todas as Vagas</a>
                </div>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    Nenhuma vaga recente encontrada. Volte em breve!
                </div>
            <?php endif; ?>

        </div>
    </section>

    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">MeuFreela &copy; 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>