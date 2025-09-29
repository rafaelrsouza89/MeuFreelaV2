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
    <meta charset="UTF-8"><title>Detalhes: <?php echo htmlspecialchars($vaga['titulo']); ?></title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <h1><?php echo htmlspecialchars($vaga['titulo']); ?></h1>
        <div class="profile-section"><p><strong>Publicado por:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p></div>
        <div class="profile-section"><h3>Descrição</h3><p><?php echo nl2br(htmlspecialchars($vaga['descricao'])); ?></p></div>
        <?php if (isset($_SESSION['user_id']) && (strtolower($_SESSION['user_type']) === 'freelancer' || strtolower($_SESSION['user_type']) === 'ambos')): ?>
            <form action="processa_candidatura.php" method="POST"><input type="hidden" name="vaga_id" value="<?php echo $vaga['id']; ?>"><input type="submit" value="Candidatar-se"></form>
        <?php endif; ?>
        <a href="procurar_vagas.php" class="button button-secondary">Voltar para a Lista</a>
    </div>
</body>
</html>