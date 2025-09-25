<?php
require_once 'includes/db.php';

// 1. Pega o token da URL e verifica se ele existe
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Token não fornecido. O link pode estar quebrado.");
}
$token = $_GET['token'];

// 2. Cria o hash do token recebido para compará-lo com o que está no banco
$token_hash = hash('sha256', $token);

try {
    // 3. Busca um usuário com este token e verifica se ele não expirou
    $sql_find = "SELECT id FROM usuario 
                 WHERE reset_token_hash = :token_hash AND reset_token_expires_at > NOW()";
    
    $stmt_find = $pdo->prepare($sql_find);
    $stmt_find->execute(['token_hash' => $token_hash]);
    $usuario = $stmt_find->fetch();

    if (!$usuario) {
        // Se não encontrar, o token é inválido ou expirou
        die("Link de recuperação inválido ou expirado. Por favor, solicite um novo.");
    }
    
    $id_usuario = $usuario['id'];
    $message = '';

    // 4. Se o formulário de nova senha for enviado (via POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $senha = $_POST['senha'];
        $senha_confirma = $_POST['senha_confirma'];

        if (empty($senha) || $senha !== $senha_confirma) {
            $message = '<p style="color: red;">As senhas não conferem ou estão vazias.</p>';
        } elseif (strlen($senha) < 6) { // Validação de complexidade mínima
            $message = '<p style="color: red;">A senha deve ter no mínimo 6 caracteres.</p>';
        } else {
            // Se tudo estiver certo, atualiza a senha
            $nova_senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // ATUALIZA a senha e LIMPA o token de recuperação para que não possa ser usado novamente
            $sql_update = "UPDATE usuario 
                           SET senha = :nova_senha, reset_token_hash = NULL, reset_token_expires_at = NULL
                           WHERE id = :id_usuario";
                           
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute(['nova_senha' => $nova_senha_hash, 'id_usuario' => $id_usuario]);
            
            // Exibe mensagem de sucesso e esconde o formulário
            echo '<h1>Senha Redefinida com Sucesso!</h1>';
            echo '<p>Sua senha foi alterada. Agora você já pode fazer login com sua nova senha.</p>';
            echo '<a href="login.php">Ir para a página de Login</a>';
            exit(); // Encerra o script
        }
    }

} catch (PDOException $e) {
    // error_log($e->getMessage());
    die("Ocorreu um erro no servidor. Tente novamente.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - MeuFreela</title>
</head>
<body>
    <h1>Crie sua Nova Senha</h1>
    <?php echo $message; // Exibe mensagens de erro do formulário ?>

    <form action="resetar_senha.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
        <label for="senha">Nova Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <label for="senha_confirma">Confirmar Nova Senha:</label><br>
        <input type="password" id="senha_confirma" name="senha_confirma" required><br><br>
        
        <input type="submit" value="Redefinir Senha">
    </form>
</body>
</html>