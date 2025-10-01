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
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas - MeuFreela</title>
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
                    <h1 class="h2">Minhas Vagas Publicadas</h1>
                    <a href="publicar_vaga.php" class="btn btn-primary">Publicar Nova Vaga</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($vagas)): ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Título da Vaga</th>
                                        <th>Candidaturas</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($vagas as $vaga): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($vaga['titulo']); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $vaga['total_candidaturas']; ?></span></td>
                                        <td><a href="ver_candidatos.php?vaga_id=<?php echo $vaga['id']; ?>" class="btn btn-sm btn-outline-primary">Ver Candidatos</a></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Você ainda não publicou nenhuma vaga.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>