<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }

$id_usuario = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $biografia = isset($_POST['biografia']) ? trim($_POST['biografia']) : null;
    $especialidades = isset($_POST['especialidades']) ? trim($_POST['especialidades']) : null;
    $portfolio_url = isset($_POST['portfolio_url']) ? trim($_POST['portfolio_url']) : null;
    
    try {
        $sql_update = "UPDATE usuario SET nome = :nome, telefone = :telefone, biografia = :biografia, especialidades = :especialidades, portfolio_url = :portfolio_url WHERE id = :id_usuario";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute(['nome' => $nome, 'telefone' => $telefone, 'biografia' => $biografia, 'especialidades' => $especialidades, 'portfolio_url' => $portfolio_url, 'id_usuario' => $id_usuario]);
        $_SESSION['user_name'] = $nome;
        $message = '<p style="color: green;">Perfil atualizado com sucesso!</p>';
    } catch (PDOException $e) { $message = '<p style="color: red;">Erro ao atualizar o perfil.</p>'; }
}

try {
    $sql_select = "SELECT nome, email, telefone, biografia, especialidades, portfolio_url FROM usuario WHERE id = :id_usuario";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute(['id_usuario' => $id_usuario]);
    $usuario = $stmt_select->fetch();
    if (!$usuario) { die("Usuário não encontrado."); }
} catch (PDOException $e) { die("Erro ao carregar dados do perfil."); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Gerenciar Perfil - MeuFreela</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header"><div class="container"><a href="index.php" class="logo">MeuFreela</a><nav class="main-nav"><a href="procurar_vagas.php">Vagas</a><a href="dashboard.php">Meu Painel</a><a href="logout.php">Sair</a></nav></div></header>
    <main><div class="container"><div class="job-listing-panel">
        <h1 style="text-align: left;">Gerenciar Meu Perfil</h1>
        <?php echo $message; ?>
        <form action="gerenciar_perfil.php" method="POST">
            <label for="nome">Nome Completo:</label><input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required>
            <label for="email">E-mail (não pode ser alterado):</label><input type="email" id="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly disabled>
            <label for="telefone">Telefone:</label><input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" required>
            <?php if (strtolower($_SESSION['user_type']) === 'freelancer' || strtolower($_SESSION['user_type']) === 'ambos'): ?>
                <hr style="margin: 2rem 0;"><h3>Informações de Freelancer</h3>
                <label for="biografia">Biografia / Resumo Profissional:</label><textarea id="biografia" name="biografia" rows="5"><?php echo htmlspecialchars($usuario['biografia'] ?? ''); ?></textarea>
                <label for="especialidades">Especialidades (separadas por vírgula):</label><input type="text" id="especialidades" name="especialidades" value="<?php echo htmlspecialchars($usuario['especialidades'] ?? ''); ?>">
                <label for="portfolio_url">Link do Portfólio:</label><input type="text" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($usuario['portfolio_url'] ?? ''); ?>">
            <?php endif; ?>
            <input type="submit" value="Salvar Alterações" style="border-radius: 5px;">
        </form>
    </div></div></main>
</body>
</html>