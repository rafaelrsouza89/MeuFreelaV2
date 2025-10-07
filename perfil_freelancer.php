<?php
session_start();
require_once 'includes/db.php';

// Pega o ID do freelancer da URL
$id_freelancer = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_freelancer) {
    die("Perfil não encontrado.");
}

try {
    // Busca os dados públicos do freelancer
    $sql = "SELECT nome, email, telefone, biografia, especialidades, portfolio_url 
            FROM usuario WHERE id = :id_freelancer AND (tipo_usuario = 'freelancer' OR tipo_usuario = 'ambos')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_freelancer' => $id_freelancer]);
    $freelancer = $stmt->fetch();

    if (!$freelancer) {
        die("Perfil de freelancer não encontrado.");
    }

} catch (PDOException $e) {
    die("Erro ao carregar o perfil.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($freelancer['nome']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos específicos para a página de perfil */
        .profile-section {
            margin-bottom: 20px;
        }
        .profile-section h3 {
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
            color: #4a148c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Perfil de Freelancer</h1>
        
        <div class="profile-section">
            <h3><?php echo htmlspecialchars($freelancer['nome']); ?></h3>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($freelancer['email']); ?></p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($freelancer['telefone']); ?></p>
        </div>

        <?php if (!empty($freelancer['biografia'])): ?>
            <div class="profile-section">
                <h3>Resumo Profissional</h3>
                <p><?php echo nl2br(htmlspecialchars($freelancer['biografia'])); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($freelancer['especialidades'])): ?>
            <div class="profile-section">
                <h3>Especialidades</h3>
                <p><?php echo htmlspecialchars($freelancer['especialidades']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($freelancer['portfolio_url'])): ?>
            <div class="profile-section">
                <h3>Portfólio</h3>
                <p><a href="<?php echo htmlspecialchars($freelancer['portfolio_url']); ?>" target="_blank">Ver portfólio</a></p>
            </div>
        <?php endif; ?>
        
        <a href="javascript:history.back()">Voltar</a>
    </div>
</body>
<a href="index.php" class="btn btn-outline-secondary mt-4 mb-2 d-inline-block">
    &larr; Voltar
</a>
</html>