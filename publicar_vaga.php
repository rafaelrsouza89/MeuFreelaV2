<?php
session_start();
// ... (lógica de verificação de acesso) ...
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Vaga - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Publicar Nova Vaga</h1>
                <form action="processa_vaga.php" method="POST">
                    <div class="mb-3"><label for="titulo" class="form-label">Título da Vaga:</label><input type="text" class="form-control" id="titulo" name="titulo" required></div>
                    
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria da Vaga:</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Selecione...</option>
                            <option value="Musicos">Músicos</option>
                            <option value="Cozinheiros">Cozinheiros</option>
                            <option value="Design Grafico">Design Gráfico</option>
                            <option value="Desenvolvimento Web">Desenvolvimento Web</option>
                            <option value="Fotografia">Fotografia</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div class="mb-3"><label for="descricao" class="form-label">Descrição:</label><textarea class="form-control" id="descricao" name="descricao" rows="5" required></textarea></div>
                    <div class="mb-3"><label for="tipo_vaga" class="form-label">Tipo da Vaga:</label><select class="form-select" id="tipo_vaga" name="tipo_vaga" required><option value="remunerado">Remunerado</option><option value="voluntario">Voluntário</option></select></div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>