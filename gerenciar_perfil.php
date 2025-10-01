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

    // LÓGICA DE UPLOAD DA FOTO
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload_dir = 'uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['foto']['type'];

        if (in_array($file_type, $allowed_types)) {
            // Gera um nome único para o arquivo para evitar sobreposições
            $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nome_foto_nova = uniqid('user_', true) . '.' . $file_extension;
            $upload_path = $upload_dir . $nome_foto_nova;

            // Move o arquivo para a pasta de uploads
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                // Se o upload foi bem-sucedido, apaga a foto antiga (se existir)
                if ($nome_foto_atual && file_exists($upload_dir . $nome_foto_atual)) {
                    unlink($upload_dir . $nome_foto_atual);
                }
                $nome_foto_atual = $nome_foto_nova; // Define o novo nome para salvar no banco
            } else {
                $message = '<div class="alert alert-danger">Erro ao salvar a imagem.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Formato de arquivo não permitido. Use JPG, PNG ou GIF.</div>';
        }
    }

    // Se não houve erro no upload da foto, continua para atualizar o banco
    if (empty($message)) {
        try {
            $sql_update = "UPDATE usuario SET nome = :nome, telefone = :telefone, foto_perfil = :foto_perfil WHERE id = :id_usuario";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                'nome' => $nome, 
                'telefone' => $telefone,
                'foto_perfil' => $nome_foto_atual,
                'id_usuario' => $id_usuario
            ]);
            
            $_SESSION['user_name'] = $nome;
            $message = '<div class="alert alert-success">Perfil atualizado com sucesso! A página será recarregada.</div>';
            // Recarrega a página para exibir a nova foto e dados
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

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>