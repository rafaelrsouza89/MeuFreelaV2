<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$id_contratante = $_SESSION['user_id'];
try {
    $sql = "SELECT v.id, v.titulo, COUNT(c.id) AS total_candidaturas FROM vaga AS v LEFT JOIN candidatura AS c ON v.id = c.vaga_id WHERE v.id_usuario = :id_contratante GROUP BY v.id ORDER BY v.data_publicacao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_contratante' => $id_contratante]);
    $vagas = $stmt->fetchAll();
} catch (PDOException $e) { $error_message = "Erro ao carregar suas vagas."; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container"><a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a><div class="d-flex"><a class="nav-link" href="dashboard.php">Meu Painel</a></div></div>
    </nav>
    <main class="container py-5">
        <h1 class="mb-4">Minhas Vagas Publicadas</h1>
        <?php if (!empty($vagas)): foreach ($vagas as $vaga): ?>
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo htmlspecialchars($vaga['titulo']); ?></h5>
                        <p class="card-text"><span class="badge bg-secondary"><?php echo $vaga['total_candidaturas']; ?> candidatura(s)</span></p>
                    </div>
                    <a href="ver_candidatos.php?vaga_id=<?php echo $vaga['id']; ?>" class="btn btn-primary">Ver Candidatos</a>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="alert alert-info">Você ainda não publicou nenhuma vaga.</div>
        <?php endif; ?>
    </main>
    <footer class="bg-light text-center py-3 mt-auto"><div class="container"><p class="mb-0">MeuFreela &copy; 2025</p></div></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>