<?php
session_start();
require_once 'includes/db.php'; 

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit(); 
}

// O ID do usuário logado (o freelancer)
$id_freelancer = $_SESSION['user_id'];
$error_message = '';

// Variável para controlar a exibição do botão de publicação
$can_publish = in_array(strtolower($_SESSION['user_type'] ?? ''), ['contratante', 'ambos']);
$candidaturas = []; // Inicializa a variável que guardará os resultados

try {
    $sql = "
        SELECT 
            v.id AS vaga_id, 
            v.titulo, 
            u.nome AS nome_contratante, 
            c.data_candidatura 
        FROM 
            candidatura AS c
        JOIN 
            vaga AS v ON c.vaga_id = v.id        
        JOIN 
            usuario AS u ON v.id_usuario = u.id  
        WHERE 
            c.id_usuario = :id_freelancer 
        ORDER BY 
            c.data_candidatura DESC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_freelancer' => $id_freelancer]);
    $candidaturas = $stmt->fetchAll(PDO::FETCH_ASSOC); 

} catch (PDOException $e) { 
    // Mantenha a exibição do erro para debug, caso ele mude
    $error_message = "Erro ao carregar suas candidaturas. Detalhe: " . $e->getMessage(); 
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Candidaturas - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <h4 class="px-3">MeuFreela</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Meu Perfil</a></li>
                        <li class="nav-item"><a class="nav-link active" href="minhas_candidaturas.php">Minhas Candidaturas</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Minhas Candidaturas</h1>
                    
                    
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($candidaturas)): ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Título da Vaga</th>
                                        <th>Contratante</th> <th>Data da Candidatura</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($candidaturas as $candidatura): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($candidatura['titulo']); ?></td>
                                        <td><?php echo htmlspecialchars($candidatura['nome_contratante']); ?></td>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($candidatura['data_candidatura'])); ?></td>
                                        <td>
                                            <a href="detalhes_vaga.php?id=<?php echo $candidatura['vaga_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                Ver Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Você ainda não se candidatou a nenhuma vaga.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>