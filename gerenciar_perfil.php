<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['user_id'];
$message = '';

// Lógica para buscar os dados atuais do usuário (executa antes de tudo)
try {
    $sql_select = "SELECT * FROM usuario WHERE id = :id_usuario";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute(['id_usuario' => $id_usuario]);
    $usuario = $stmt_select->fetch();
    if (!$usuario) { die("Usuário não encontrado."); }
} catch (PDOException $e) {
    die("Erro ao carregar dados do perfil.");
}

// Processar o formulário se for enviado (MÉTODO POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $nome_foto_atual = $usuario['foto_perfil'];

    // Coleta dados de freelancer se aplicável
    $biografia = (isset($_POST['biografia'])) ? trim($_POST['biografia']) : null;
    $especialidades = (isset($_POST['especialidades'])) ? trim($_POST['especialidades']) : null;
    $portfolio_url = (isset($_POST['portfolio_url'])) ? trim($_POST['portfolio_url']) : null;


    // LÓGICA DE UPLOAD DA FOTO
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['foto']['type'];

        if (in_array($file_type, $allowed_types)) {
            $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nome_foto_nova = uniqid('user_', true) . '.' . $file_extension;
            $upload_path = $upload_dir . $nome_foto_nova;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                if ($nome_foto_atual && file_exists($upload_dir . $nome_foto_atual)) {
                    unlink($upload_dir . $nome_foto_atual);
                }
                $nome_foto_atual = $nome_foto_nova;
            } else {
                $message = '<div class="alert alert-danger">Erro ao salvar a imagem.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Formato de arquivo não permitido. Use JPG, PNG ou GIF.</div>';
        }
    }

    if (empty($message)) {
        try {
            $sql_update = "UPDATE usuario SET nome = :nome, telefone = :telefone, biografia = :biografia, especialidades = :especialidades, portfolio_url = :portfolio_url, foto_perfil = :foto_perfil WHERE id = :id_usuario";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                'nome' => $nome, 
                'telefone' => $telefone,
                'biografia' => $biografia,
                'especialidades' => $especialidades,
                'portfolio_url' => $portfolio_url,
                'foto_perfil' => $nome_foto_atual,
                'id_usuario' => $id_usuario
            ]);
            
            $_SESSION['user_name'] = $nome;
            $message = '<div class="alert alert-success">Perfil atualizado com sucesso! A página será recarregada.</div>';
            echo "<meta http-equiv='refresh' content='2'>";

        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Ocorreu um erro ao atualizar o perfil no banco.</div>';
        }
    }
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
                
                <form action="gerenciar_perfil.php" method="POST" class="mt-4" enctype="multipart/form-data">
                    
                    <div class="text-center mb-4">
                        <?php
                        $foto = 'uploads/' . ($usuario['foto_perfil'] ?? 'default-avatar.png');
                        if (!file_exists($foto) || empty($usuario['foto_perfil'])) {
                            $foto = 'https://via.placeholder.com/150'; // Imagem padrão
                        }
                        ?>
                        <img src="<?php echo $foto; ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Alterar Foto de Perfil:</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo:</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required>
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

                <div class="text-center mt-3">
                    <a href="dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<a href="index.php" class="btn btn-outline-secondary mt-4 mb-2 d-inline-block">
    &larr; Voltar
</a>
</html>