<?php
session_start();
require_once 'includes/db.php';
$id_vaga = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_vaga) { die("Vaga não encontrada."); }
try {
    $sql = "SELECT v.*, u.nome AS nome_contratante FROM vaga AS v JOIN usuario AS u ON v.id_usuario = u.id WHERE v.id = :id_vaga";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_vaga' => $id_vaga]);
    $vaga = $stmt->fetch();
    if (!$vaga) { die("Vaga não encontrada."); }
} catch (PDOException $e) { die("Erro ao consultar a vaga."); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes: <?php echo htmlspecialchars($vaga['titulo']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
            <div class="d-flex">
                <a class="nav-link me-3" href="procurar_vagas.php">Vagas</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="dashboard.php">Meu Painel</a>
                <?php else: ?>
                    <a class="btn btn-primary rounded-pill" href="login.php">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="d-flex justify-content-end mb-4">
            <button onclick="history.back()" class="btn btn-outline-secondary">
                &larr; Voltar
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h1><?php echo htmlspecialchars($vaga['titulo']); ?></h1>
            </div>
            <div class="card-body">
                <p><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                <p><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                <p><strong>Remuneração:</strong> <?php echo $vaga['tipo_vaga'] === 'voluntario' ? 'Trabalho Voluntário' : 'R$ ' . number_format($vaga['remuneracao'], 2, ',', '.'); ?></p>
                <hr>
                <h5 class="card-title">Descrição da Vaga</h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($vaga['descricao'])); ?></p>

                <?php 
                $is_freelancer_or_ambos = isset($_SESSION['user_type']) && 
                                          (strtolower($_SESSION['user_type']) === 'freelancer' || 
                                           strtolower($_SESSION['user_type']) === 'ambos');
                                           
                if (isset($_SESSION['user_id']) && $is_freelancer_or_ambos): ?>
                    <form action="processa_candidatura.php" method="POST" class="mt-4">
                        <input type="hidden" name="vaga_id" value="<?php echo $vaga['id']; ?>">
                        <button type="submit" class="btn btn-success btn-lg">Candidatar-se a esta Vaga</button>
                    </form>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <div class="alert alert-warning mt-4" role="alert">
                        <a href="login.php">Faça login como freelancer</a> para se candidatar.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container"><p class="mb-0">MeuFreela &copy; 2025</p></div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>