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

    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">MeuFreela</a>
            <nav class="main-nav">
                <a href="procurar_vagas.php">Vagas</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Meu Painel</a><a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a><a href="cadastro.php" class="button-primary">Cadastrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="job-listing-panel">
                <h1 style="text-align: left;"><?php echo htmlspecialchars($vaga['titulo']); ?></h1>
                <p><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                <p><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                <p><strong>Remuneração:</strong> <?php echo $vaga['tipo_vaga'] === 'voluntario' ? 'Trabalho Voluntário' : 'R$ ' . number_format($vaga['remuneracao'], 2, ',', '.'); ?></p>
                <hr style="margin: 2rem 0;">
                <h3>Descrição da Vaga</h3>
                <p><?php echo nl2br(htmlspecialchars($vaga['descricao'])); ?></p>
                
                <?php if (isset($_SESSION['user_id']) && (strtolower($_SESSION['user_type']) === 'freelancer' || strtolower($_SESSION['user_type']) === 'ambos')): ?>
                    <form action="processa_candidatura.php" method="POST" style="margin-top: 2rem;">
                        <input type="hidden" name="vaga_id" value="<?php echo $vaga['id']; ?>">
                        <input type="submit" value="Candidatar-se a esta Vaga" style="border-radius: 5px;">
                    </form>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <p style="text-align: center; font-weight: bold; margin-top: 2rem;">
                        <a href="login.php">Faça login como freelancer</a> para se candidatar.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>