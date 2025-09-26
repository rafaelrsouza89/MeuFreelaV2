<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$userName = $_SESSION['user_name'];
// CORREÇÃO: Convertemos o tipo de usuário para minúsculas antes de verificar
$userType = strtolower($_SESSION['user_type']); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel - MeuFreela</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="panel">
        <div style="text-align: right;">
             <a href="logout.php">Sair</a>
        </div>
        <h1>Bem-vindo, <?php echo htmlspecialchars($userName); ?>!</h1>

        <main>
            <h2>Ações Rápidas</h2>
            
            <?php // A verificação agora funciona corretamente ?>
            <?php if ($userType === 'contratante' || $userType === 'ambos'): ?>
                <div class="profile-section">
                    <h3>Área do Contratante</h3>
                    <p><a href="publicar_vaga.php" class="button">Publicar Nova Vaga</a></p>
                    <p><a href="minhas_vagas.php">Gerenciar Minhas Vagas</a></p>
                </div>
            <?php endif; ?>

            <?php if ($userType === 'freelancer' || $userType === 'ambos'): ?>
                 <div class="profile-section">
                    <h3>Área do Freelancer</h3>
                    <p><a href="procurar_vagas.php">Procurar Vagas</a></p>
                </div>
            <?php endif; ?>

             <div class="profile-section">
                <h3>Sua Conta</h3>
                <p><a href="gerenciar_perfil.php">Gerenciar Meu Perfil</a></p>
            </div>
        </main>
    </div>

</body>
</html>