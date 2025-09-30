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
    
    // Coleta dos dados de freelancer (APENAS se o usuário for freelancer)
    $biografia = null;
    $especialidades = null;
    $portfolio_url = null;
    if (strtolower($_SESSION['user_type']) === 'freelancer' || strtolower($_SESSION['user_type']) === 'ambos') {
        $biografia = isset($_POST['biografia']) ? trim($_POST['biografia']) : null;
        $especialidades = isset($_POST['especialidades']) ? trim($_POST['especialidades']) : null;
        $portfolio_url = isset($_POST['portfolio_url']) ? trim($_POST['portfolio_url']) : null;
    }
    
    // Validação
    if (empty($nome) || empty($telefone)) {
        $message = '<div class="alert alert-danger">Nome e telefone são obrigatórios.</div>';
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
            
            // Atualiza o nome na sessão para que a mudança apareça no site
            $_SESSION['user_name'] = $nome;
            
            $message = '<div class="alert alert-success">Perfil atualizado com sucesso!</div>';
            
        } catch (PDOException $e) {
            // Para depuração, você pode descomentar a linha abaixo para ver o erro exato do banco.
            // $message = '<div class="alert alert-danger">Erro: ' . $e->getMessage() . '</div>';
            $message = '<div class="alert alert-danger">Ocorreu um erro ao atualizar o perfil. Tente novamente.</div>';
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
    die("Erro ao carregar dados do perfil.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Perfil - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">MeuFreela</a>
            <div class="d-flex">
                <a class="nav-link me-3" href="dashboard.php">Meu Painel</a>
                <a class="nav-link" href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Gerenciar Meu Perfil</h1>
                <?php if (!empty($message)) { echo $message; } ?>
                <form action="gerenciar_perfil.php" method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo:</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail (não pode ser alterado):</label>
                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone:</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" required>
                    </div>

                    <?php if (strtolower($_SESSION['user_type']) === 'freelancer' || strtolower($_SESSION['user_type']) === 'ambos'): ?>
                        <hr class="my-4">
                        <h4>Informações de Freelancer</h4>
                        <div class="mb-3">
                            <label for="biografia" class="form-label">Biografia / Resumo Profissional:</label>
                            <textarea class="form-control" id="biografia" name="biografia" rows="5"><?php echo htmlspecialchars($usuario['biografia'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="especialidades" class="form-label">Especialidades (separadas por vírgula):</label>
                            <input type="text" class="form-control" id="especialidades" name="especialidades" value="<?php echo htmlspecialchars($usuario['especialidades'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="portfolio_url" class="form-label">Link do Portfólio:</label>
                            <input type="text" class="form-control" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($usuario['portfolio_url'] ?? ''); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container"><p class="mb-0">MeuFreela &copy; 2025</p></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>