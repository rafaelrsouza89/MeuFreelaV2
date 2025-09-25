<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    try {
        // 1. Verifica se o usuário com o e-mail fornecido existe
        $sql_find_user = "SELECT id FROM usuario WHERE email = :email";
        $stmt_find_user = $pdo->prepare($sql_find_user);
        $stmt_find_user->execute(['email' => $email]);
        $usuario = $stmt_find_user->fetch();

        if ($usuario) {
            // 2. Gera um token de segurança único e aleatório
            $token = bin2hex(random_bytes(32));
            
            // 3. Define um prazo de validade (ex: 1 hora a partir de agora)
            $expires_at = new DateTime();
            $expires_at->modify('+1 hour');
            $expires_at_format = $expires_at->format('Y-m-d H:i:s');
            
            // 4. Cria um hash do token para armazenar no banco de dados com segurança
            $token_hash = hash('sha256', $token);
            
            // 5. Atualiza o registro do usuário com o token e a data de expiração
            $sql_update = "UPDATE usuario 
                           SET reset_token_hash = :token_hash, reset_token_expires_at = :expires_at 
                           WHERE id = :user_id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                'token_hash' => $token_hash,
                'expires_at' => $expires_at_format,
                'user_id' => $usuario['id']
            ]);

            // --- SIMULAÇÃO DE ENVIO DE E-MAIL ---
            // Em um sistema real, você usaria uma biblioteca como PHPMailer para enviar o e-mail.
            // Por enquanto, vamos exibir o link na tela para podermos testar.
            
            // Monta a URL do link de recuperação
            // Certifique-se de que o caminho 'http://localhost/MeuFreela/' está correto para o seu ambiente
            $reset_link = "http://localhost/MeuFreela/resetar_senha.php?token=" . $token;

            echo "<h2>Link de Recuperação Gerado (Simulação)</h2>";
            echo "<p>Em um sistema real, o link a seguir seria enviado para o seu e-mail:</p>";
            echo '<a href="' . $reset_link . '">' . $reset_link . '</a>';
            echo '<p><a href="recuperar_senha.php">Voltar</a></p>';
            exit(); // Encerra o script aqui para não redirecionar
        }

    } catch (PDOException $e) {
        // error_log($e->getMessage());
        header('Location: recuperar_senha.php?status=error');
        exit();
    }
    
    // Se o e-mail não foi encontrado, redirecionamos para a mesma página com uma mensagem de sucesso genérica.
    // Isso evita que alguém possa usar este formulário para descobrir quais e-mails estão cadastrados.
    header('Location: recuperar_senha.php?status=success');
    exit();
}
?>