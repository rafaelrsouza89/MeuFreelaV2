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

// verifica se usuário já se candidatou
$already_applied = false;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM candidatura WHERE id_usuario = :uid AND vaga_id = :vid");
        $stmt2->execute(['uid' => $_SESSION['user_id'], 'vid' => $id_vaga]);
        $already_applied = (int)$stmt2->fetchColumn() > 0;
    } catch (PDOException $e) {
        // ignore - mantém $already_applied = false
    }
}

// mensagens via GET
$alert_success = '';
$alert_error = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'aplicado') {
    $alert_success = 'Candidatura realizada com sucesso.';
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'ja_aplicou') {
    $alert_error = 'Você já está inscrito nesta vaga.';
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'erro') {
    $alert_error = 'Ocorreu um erro ao processar sua candidatura.';
}
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
        <?php if ($alert_success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($alert_success) ?></div>
        <?php endif; ?>
        <?php if ($alert_error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($alert_error) ?></div>
        <?php endif; ?>

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
                // aceita tanto 'user_type' quanto 'tipo_usuario' em sessão
                $sess_tipo = $_SESSION['user_type'] ?? $_SESSION['tipo_usuario'] ?? null;
                $is_freelancer_or_ambos = $sess_tipo && (strtolower($sess_tipo) === 'freelancer' || strtolower($sess_tipo) === 'freelance' || strtolower($sess_tipo) === 'ambos');
                ?>

                <?php if (isset($_SESSION['user_id']) && $is_freelancer_or_ambos): ?>
                    <?php if ($already_applied): ?>
                        <button class="btn btn-secondary btn-lg" disabled>Já inscrito</button>
                    <?php else: ?>
                        <form action="processa_candidatura.php" method="POST" class="mt-4 d-inline">
                            <input type="hidden" name="vaga_id" value="<?php echo $vaga['id']; ?>">
                            <button type="submit" class="btn btn-success btn-lg">Candidatar-se a esta Vaga</button>
                        </form>
                    <?php endif; ?>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <div class="alert alert-warning mt-4" role="alert">
                        <a href="login.php?redirect=detalhes_vaga.php?id=<?php echo $vaga['id']; ?>">Faça login como freelancer</a> para se candidatar.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-4">Somente freelancers podem se candidatar a vagas.</div>
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