<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$userName = $_SESSION['user_name'];
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
        
            
            <?php if ($userType === 'contratante' || $userType === 'ambos'): ?>
                <div class="profile-section">
                    <h3>Área do Contratante</h3>
                    <a href="publicar_vaga.php" class="button">Publicar Nova Vaga</a>
                    <a href="minhas_vagas.php" class="button">Gerenciar Minhas Vagas</a>
                </div>
            <?php endif; ?>

            <?php if ($userType === 'freela
ncer' || $userType === 'ambos'): ?>
                 <div class="profile-section">
                    <h3>Área do Freelancer</h3>
                    <a href="procurar_vagas.php" class="button">Procurar Vagas</a>
                </div>
            <?php endif; ?>

             <div class="profile-section">
                <h3>Sua Conta</h3>
                <a href="gerenciar_perfil.php" class="button">Gerenciar Meu Perfil</a>
            </div>
        </main>
    </div>

</body>
</html>