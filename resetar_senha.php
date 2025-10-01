<?php
require_once 'includes/db.php';
$message = '';
$token_valido = false;

if (!isset($_GET['token']) || empty($_GET['token'])) {
    $message = '<div class="alert alert-danger">Token não fornecido. O link pode estar quebrado.</div>';
} else {
    $token = $_GET['token'];
    $token_hash = hash('sha256', $token);
    
    $sql_find = "SELECT id FROM usuario WHERE reset_token_hash = :token_hash AND reset_token_expires_at > NOW()";
    $stmt_find = $pdo->prepare($sql_find);
    $stmt_find->execute(['token_hash' => $token_hash]);
    $usuario = $stmt_find->fetch();

    if ($usuario) {
        $token_valido = true;
        $id_usuario = $usuario['id'];
    } else {
        $message = '<div class="alert alert-danger">Link de recuperação inválido ou expirado. Por favor, solicite um novo.</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valido) {
    $senha = $_POST['senha'];
    $senha_confirma = $_POST['senha_confirma'];

    if (empty($senha) || $senha !== $senha_confirma) {
        $message = '<div class="alert alert-danger">As senhas não conferem ou estão vazias.</div>';
    } elseif (strlen($senha) < 6) {
        $message = '<div class="alert alert-danger">A senha deve ter no mínimo 6 caracteres.</div>';
    } else {
        $nova_senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql_update = "UPDATE usuario SET senha = :nova_senha, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = :id_usuario";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute(['nova_senha' => $nova_senha_hash, 'id_usuario' => $id_usuario]);
        
        $message = '<div class="alert alert-success">Senha redefinida com sucesso! Você já pode fazer login com sua nova senha.</div>';
        $token_valido = false; // Esconde o formulário após o sucesso
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Redefinir Senha</h3></div>
                    <div class="card-body">
                        <?php if (!empty($message)) { echo $message; } ?>

                        <?php if ($token_valido): ?>
                            <form action="resetar_senha.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="senha" name="senha" type="password" placeholder="Nova Senha" required />
                                    <label for="senha">Nova Senha</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="senha_confirma" name="senha_confirma" type="password" placeholder="Confirmar Nova Senha" required />
                                    <label for="senha_confirma">Confirmar Nova Senha</label>
                                </div>
                                <div class="d-grid mt-4">
                                    <button class="btn btn-primary btn-lg" type="submit">Redefinir Senha</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="login.php">Voltar para a página de login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>