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
    <meta charset="UTF-8"><title>Minhas Vagas - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header"><div class="container"><a href="index.php" class="logo">MeuFreela</a><nav class="main-nav"><a href="dashboard.php">Meu Painel</a><a href="logout.php">Sair</a></nav></div></header>
    <main><div class="container"><div class="job-listing-panel">
        <h1 style="text-align: left;">Minhas Vagas Publicadas</h1>
        <?php if (!empty($vagas)): foreach ($vagas as $vaga): ?>
            <div class="job-card">
                <div><h3><?php echo htmlspecialchars($vaga['titulo']); ?></h3><p><?php echo $vaga['total_candidaturas']; ?> candidatura(s)</p></div>
                <div><a href="ver_candidatos.php?vaga_id=<?php echo $vaga['id']; ?>" class="button-primary" style="text-decoration: none;">Ver Candidatos</a></div>
            </div>
        <?php endforeach; else: ?>
            <p>Você ainda não publicou nenhuma vaga.</p>
        <?php endif; ?>
    </div></div></main>
</body>
</html>