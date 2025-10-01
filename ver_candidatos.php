<?php
session_start();
require_once 'includes/db.php';

// Verificação de segurança: usuário logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_vaga = filter_input(INPUT_GET, 'vaga_id', FILTER_VALIDATE_INT);
$id_contratante = $_SESSION['user_id'];
$titulo_vaga = ''; // Inicializa a variável para evitar erros
$candidatos = [];

if (!$id_vaga) {
    die("ID da vaga inválido.");
}

try {
    // Verificação de segurança: garante que a vaga pertence ao contratante logado
    $sql_check_owner = "SELECT titulo FROM vaga WHERE id = :id_vaga AND id_usuario = :id_contratante";
    $stmt_check_owner = $pdo->prepare($sql_check_owner);
    $stmt_check_owner->execute(['id_vaga' => $id_vaga, 'id_contratante' => $id_contratante]);
    $vaga = $stmt_check_owner->fetch();

    if (!$vaga) {
        die("Acesso não autorizado ou vaga inexistente.");
    }
    
    // Se a verificação passar, define o título da vaga
    $titulo_vaga = $vaga['titulo'];

    // Agora, busca os candidatos para a vaga
    $sql_get_candidates = "SELECT u.id, u.nome, u.email, u.telefone, c.data_candidatura FROM candidatura AS c JOIN usuario AS u ON c.id_usuario = u.id WHERE c.vaga_id = :id_vaga ORDER BY c.data_candidatura DESC";
    $stmt_get_candidates = $pdo->prepare($sql_get_candidates);
    $stmt_get_candidates->execute(['id_vaga' => $id_vaga]);
    $candidatos = $stmt_get_candidates->fetchAll();

} catch (PDOException $e) {
    die("Erro ao consultar os dados.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos para <?php echo htmlspecialchars($titulo_vaga); ?> - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #fff; height: 100vh; padding-top: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: #555; font-weight: 500; }
        .sidebar .nav-link.active { color: #0d6efd; background-color: #e9f5ff; border-right: 3px solid #0d6efd; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <h4 class="px-3">MeuFreela</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Meu Perfil</a></li>
                        <li class="nav-item"><a class="nav-link active" href="minhas_vagas.php">Minhas Vagas</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Candidatos para "<?php echo htmlspecialchars($titulo_vaga); ?>"</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                         <?php if (!empty($candidatos)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nome do Candidato</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($candidatos as $candidato): ?>
                                        <tr>
                                            <td>
                                                <a href="perfil_freelancer.php?id=<?php echo $candidato['id']; ?>" target="_blank">
                                                    <?php echo htmlspecialchars($candidato['nome']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($candidato['email']); ?></td>
                                            <td><?php echo htmlspecialchars($candidato['telefone']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-center">Nenhum candidato para esta vaga ainda.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>