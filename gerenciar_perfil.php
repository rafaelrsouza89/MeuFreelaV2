<?php
session_start();
require_once 'includes/db.php';

// 1. Segurança: Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['user_id'];
$message = ''; // Variável para exibir mensagens de sucesso ou erro

// 2. Processamento do formulário (quando ele for enviado via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta dos dados gerais
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    
    // Coleta dos dados de freelancer (se existirem no formulário)
    $biografia = isset($_POST['biografia']) ? trim($_POST['biografia']) : null;
    $especialidades = isset($_POST['especialidades']) ? trim($_POST['especialidades']) : null;
    $portfolio_url = isset($_POST['portfolio_url']) ? trim($_POST['portfolio_url']) : null;
    
    // Validação simples
    if (empty($nome) || empty($telefone)) {
        $message = '<p style="color: red;">Nome e telefone são obrigatórios.</p>';
    } else {
        try {
            // Query para ATUALIZAR os dados do usuário
            $sql_update = "UPDATE usuario SET 
                                nome = :nome, 
                                telefone = :telefone,
                                biografia = :biografia,
                                especialidades = :especialidades,
                                portfolio_url = :portfolio_url
                           WHERE id = :id_usuario";

            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                'nome' => $nome, 
                'telefone' => $telefone,
                'biografia' => $biografia,
                'especialidades' => $especialidades,
                'portfolio_url' => $portfolio_url,
                'id_usuario' => $id_usuario
            ]);
            
            // Atualiza o nome na sessão para que a mudança apareça imediatamente no site
            $_SESSION['user_name'] = $nome;
            
            $message = '<p style="color: green;">Perfil atualizado com sucesso!</p>';
            
        } catch (PDOException $e) {
            // error_log($e->getMessage()); // Em produção, é bom registrar o erro
            $message = '<p style="color: red;">Erro ao atualizar o perfil. Tente novamente.</p>';
        }
    }
}

// 3. Busca os dados atuais do usuário para preencher o formulário
try {
    $sql_select = "SELECT nome, email, telefone, biografia, especialidades, portfolio_url 
                   FROM usuario WHERE id = :id_usuario";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute(['id_usuario' => $id_usuario]);
    $usuario = $stmt_select->fetch();
    
    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} catch (PDOException $e) {
    // error_log($e->getMessage());
    die("Erro ao carregar dados do perfil.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Meu Perfil - MeuFreela</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="panel">
        <div style="text-align: right;"><a href="dashboard.php">Voltar para o Painel</a></div>
        <h1>Gerenciar Meu Perfil</h1>
        
        <?php echo $message; // Exibe a mensagem de status aqui ?>
        
        <form action="gerenciar_perfil.php" method="POST">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required>

            <label for="email">E-mail (não pode ser alterado):</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly disabled style="background-color: #f0f0f0;">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" required>

            <?php if ($_SESSION['user_type'] === 'freelancer' || $_SESSION['user_type'] === 'ambos'): ?>
                <hr style="margin: 2rem 0;">
                <h3>Informações de Freelancer</h3>
                
                <label for="biografia">Biografia / Resumo Profissional:</label>
                <textarea id="biografia" name="biografia" rows="5"><?php echo htmlspecialchars($usuario['biografia'] ?? ''); ?></textarea>
                
                <label for="especialidades">Especialidades (separadas por vírgula):</label>
                <input type="text" id="especialidades" name="especialidades" value="<?php echo htmlspecialchars($usuario['especialidades'] ?? ''); ?>" placeholder="Ex: PHP, JavaScript, Design">

                <label for="portfolio_url">Link do Portfólio:</label>
                <input type="text" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($usuario['portfolio_url'] ?? ''); ?>" placeholder="https://exemplo.com">
            <?php endif; ?>

            <input type="submit" value="Salvar Alterações">
        </form>
    </div>
</body>
</html>