<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Painel - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">MeuFreela</a>
            <nav class="main-nav">
                <a href="procurar_vagas.php">Vagas</a><a href="gerenciar_perfil.php">Meu Perfil</a><a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="job-listing-panel">
                <h1>Painel de Controle</h1>
                <h2>Olá, <?php echo htmlspecialchars($userName); ?>!</h2>
                </div>
        </div>
    </main>
</body>
</html>