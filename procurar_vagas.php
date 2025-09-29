<?php
session_start();
require_once 'includes/db.php';
try {
    $sql = "SELECT v.id, v.titulo, v.remuneracao, v.local, u.nome AS nome_contratante FROM vaga AS v JOIN usuario AS u ON v.id_usuario = u.id WHERE v.data_limite >= CURDATE() ORDER BY v.data_publicacao DESC";
    $vagas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) { $error_message = "Erro ao buscar vagas."; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Procurar Vagas - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <h1>Vagas Disponíveis</h1>
        <main>
            <?php if (!empty($vagas)): foreach ($vagas as $vaga): ?>
                <div class="vaga-card">
                    <h3><?php echo htmlspecialchars($vaga['titulo']); ?></h3>
                    <p><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                    <p><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                    <p><strong>Remuneração:</strong> R$ <?php echo number_format($vaga['remuneracao'], 2, ',', '.'); ?></p>
                    <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="button">Ver Detalhes</a>
                </div>
            <?php endforeach; else: ?>
                <p>Nenhuma vaga disponível no momento.</p>
            <?php endif; ?>
        </main>
        <a href="<?php echo isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php'; ?>" class="button button-secondary">Voltar</a>
    </div>
</body>
</html>