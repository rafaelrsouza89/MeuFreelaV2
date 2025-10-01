<?php
session_start();
require_once 'includes/db.php';
// ... (lógica PHP para buscar o título da vaga e a lista de candidatos) ...
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ... (mesmo CSS do sidebar da página anterior) ... */
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
                <h1 class="h2">Candidatos para "<?php echo htmlspecialchars($titulo_vaga); ?>"</h1>
                <div class="card mt-4">
                    <div class="card-body">
                         <?php if (!empty($candidatos)): ?>
                            <table class="table">
                                <thead><tr><th>Nome</th><th>Email</th><th>Telefone</th></tr></thead>
                                <tbody>
                                <?php foreach ($candidatos as $candidato): ?>
                                    <tr>
                                        <td><a href="perfil_freelancer.php?id=<?php echo $candidato['id']; ?>" target="_blank"><?php echo htmlspecialchars($candidato['nome']); ?></a></td>
                                        <td><?php echo htmlspecialchars($candidato['email']); ?></td>
                                        <td><?php echo htmlspecialchars($candidato['telefone']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
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