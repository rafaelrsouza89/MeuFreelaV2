<?php
session_start();
// ... (lógica PHP de verificação de acesso) ...
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Vaga - MeuFreela</title>
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
                <h1 class="h2">Publicar Nova Vaga</h1>
                <div class="card mt-4">
                    <div class="card-body">
                        <form action="processa_vaga.php" method="POST">
                            <div class="mb-3"><label for="titulo" class="form-label">Título da Vaga:</label><input type="text" class="form-control" id="titulo" name="titulo" required></div>
                            <div class="mb-3"><label for="descricao" class="form-label">Descrição:</label><textarea class="form-control" id="descricao" name="descricao" rows="5" required></textarea></div>
                            <button type="submit" class="btn btn-primary">Publicar</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>