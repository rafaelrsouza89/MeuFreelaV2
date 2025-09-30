<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$userName = $_SESSION['user_name'];
$userType = strtolower($_SESSION['user_type']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
            <div class="d-flex">
                <a class="nav-link me-3" href="procurar_vagas.php">Vagas</a>
                <a class="nav-link" href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Painel de Controle</h1>
                <h2 class="card-subtitle mb-4 text-muted">Olá, <?php echo htmlspecialchars($userName); ?>!</h2>

                <?php if ($userType === 'contratante' || $userType === 'ambos'): ?>
                    <div class="mb-4">
                        <h4>Área do Contratante</h4>
                        <p><a href="publicar_vaga.php" class="btn btn-primary">Publicar Nova Vaga</a></p>
                        <p><a href="minhas_vagas.php" class="btn btn-secondary">Gerenciar Minhas Vagas</a></p>
                    </div>
                <?php endif; ?>

                <?php if ($userType === 'freelancer' || $userType === 'ambos'): ?>
                    <div class="mb-4">
                        <h4>Área do Freelancer</h4>
                        <p><a href="procurar_vagas.php" class="btn btn-info text-white">Procurar Vagas</a></p>
                    </div>
                <?php endif; ?>

                <div>
                    <h4>Sua Conta</h4>
                    <p><a href="gerenciar_perfil.php" class="btn btn-outline-primary">Gerenciar Meu Perfil</a></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container"><p class="mb-0">MeuFreela &copy; 2025</p></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>