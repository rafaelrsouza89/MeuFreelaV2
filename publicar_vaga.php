<?php
session_start();
$userType = isset($_SESSION['user_type']) ? strtolower($_SESSION['user_type']) : '';
if (!isset($_SESSION['user_id']) || ($userType !== 'contratante' && $userType !== 'ambos')) {
    header('Location: login.php?error=unauthorized'); exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Publicar Vaga - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <h1>Publicar Nova Vaga</h1>
        <form action="processa_vaga.php" method="POST">
            <label for="titulo">Título da Vaga:</label><input type="text" id="titulo" name="titulo" required>
            <label for="descricao">Descrição:</label><textarea id="descricao" name="descricao" rows="5" required></textarea>
            <label for="tipo_vaga">Tipo da Vaga:</label>
            <select id="tipo_vaga" name="tipo_vaga" required><option value="remunerado">Remunerado</option><option value="voluntario">Voluntário</option></select>
            <label for="remuneracao">Remuneração (R$):</label><input type="number" id="remuneracao" name="remuneracao" step="0.01" placeholder="Deixe 0 se for voluntário">
            <label for="local">Local:</label><input type="text" id="local" name="local" required>
            <label for="data_limite">Data Limite para Candidaturas:</label><input type="date" id="data_limite" name="data_limite" required>
            <input type="submit" value="Publicar Vaga">
        </form>
        <a href="dashboard.php" class="button button-secondary">Voltar ao Painel</a>
    </div>
</body>
</html>