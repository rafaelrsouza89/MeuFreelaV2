<?php
session_start();

// 1. Verifica se o usuário NÃO está logado. Se não estiver, redireciona para o login.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 2. Verifica se o usuário logado TEM permissão para publicar (Contratante ou Ambos).
// Se NÃO tiver, redireciona para o dashboard com um erro.
if (!in_array($_SESSION['user_type'], ['contratante', 'ambos'])) {
    // Redireciona para o painel de controle, pois o usuário já está logado
    header('Location: dashboard.php?error=permission_denied');
    exit();
}

// Se o código chegar até aqui, o usuário está logado E tem a permissão correta.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Vaga - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
 </head>
<body>
    <main class="container py-5 flex-grow-1">
        
        
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Publicar Nova Vaga</h1>
                <form action="processa_vaga.php" method="POST">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título da Vaga:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição:</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_vaga" class="form-label">Tipo da Vaga:</label>
                        <select class="form-select" id="tipo_vaga" name="tipo_vaga" required>
                            <option value="remunerado">Remunerado</option>
                            <option value="voluntario">Voluntário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remuneracao" class="form-label">Remuneração (R$):</label>
                        <input type="number" step="0.01" class="form-control" id="remuneracao" name="remuneracao">
                    </div>
                    <div class="mb-3">
                        <label for="local" class="form-label">Local:</label>
                        <input type="text" class="form-control" id="local" name="local" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_limite" class="form-label">Data Limite:</label>
                        <input type="date" class="form-control" id="data_limite" name="data_limite" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </div>
        </div>
         <div class="d-flex justify-content-start mb-4">
            <button onclick="history.back()" class="btn btn-outline-secondary">
                &larr; Voltar
            </button>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
