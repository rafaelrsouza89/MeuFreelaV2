<?php
session_start();
require_once 'includes/db.php';

try {
    // A lógica PHP para buscar as vagas não muda
    $sql = "SELECT 
                v.id, 
                v.titulo, 
                v.local, 
                u.nome AS nome_contratante
            FROM vaga AS v
            JOIN usuario AS u ON v.id_usuario = u.id
            WHERE v.data_limite >= CURDATE()
            ORDER BY v.data_publicacao DESC";

    $stmt = $pdo->query($sql);
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">MeuFreela</a>
            <nav class="main-nav">
                <a href="procurar_vagas.php">Vagas</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Meu Painel</a>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                    <a href="cadastro.php" class="button-primary">Cadastrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="job-listing-panel">
                <h1 style="text-align: left; margin-bottom: 2rem;">Vagas Disponíveis</h1>

                <?php if (!empty($vagas)): ?>
                    <?php foreach ($vagas as $vaga): ?>
                        <div class="job-card">
                            <div>
                                <h3><?php echo htmlspecialchars($vaga['titulo']); ?></h3>
                                <p><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                                <p><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                            </div>
                            <div>
                                <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="button-primary" style="text-decoration: none;">Ver Detalhes</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php elseif (isset($error_message)): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php else: ?>
                    <p>Nenhuma vaga disponível no momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>