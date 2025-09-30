<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$id_vaga = filter_input(INPUT_GET, 'vaga_id', FILTER_VALIDATE_INT);
$id_contratante = $_SESSION['user_id'];
if (!$id_vaga) { die("ID da vaga inválido."); }
try {
    $sql_check_owner = "SELECT titulo FROM vaga WHERE id = :id_vaga AND id_usuario = :id_contratante";
    $stmt_check_owner = $pdo->prepare($sql_check_owner);
    $stmt_check_owner->execute(['id_vaga' => $id_vaga, 'id_contratante' => $id_contratante]);
    $vaga = $stmt_check_owner->fetch();
    if (!$vaga) { die("Acesso não autorizado."); }
    $titulo_vaga = $vaga['titulo'];
    $sql_get_candidates = "SELECT u.id, u.nome, u.email, u.telefone, c.data_candidatura FROM candidatura AS c JOIN usuario AS u ON c.id_usuario = u.id WHERE c.vaga_id = :id_vaga ORDER BY c.data_candidatura DESC";
    $stmt_get_candidates = $pdo->prepare($sql_get_candidates);
    $stmt_get_candidates->execute(['id_vaga' => $id_vaga]);
    $candidatos = $stmt_get_candidates->fetchAll();
} catch (PDOException $e) { die("Erro ao consultar os dados."); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Candidatos - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header"><div class="container"><a href="index.php" class="logo">MeuFreela</a><nav class="main-nav"><a href="dashboard.php">Meu Painel</a><a href="logout.php">Sair</a></nav></div></header>
    <main><div class="container"><div class="job-listing-panel">
        <h1 style="text-align: left;">Candidatos para "<?php echo htmlspecialchars($titulo_vaga); ?>"</h1>
        <?php if (!empty($candidatos)): foreach ($candidatos as $candidato): ?>
            <div class="job-card">
                <div><h3><a href="perfil_freelancer.php?id=<?php echo $candidato['id']; ?>" target="_blank"><?php echo htmlspecialchars($candidato['nome']); ?></a></h3><p><strong>Email:</strong> <?php echo htmlspecialchars($candidato['email']); ?> | <strong>Telefone:</strong> <?php echo htmlspecialchars($candidato['telefone']); ?></p></div>
            </div>
        <?php endforeach; else: ?>
            <p>Nenhum candidato para esta vaga ainda.</p>
        <?php endif; ?>
    </div></div></main>
</body>
</html>