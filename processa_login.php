<?php
// Inicia a sessão. Isso é OBRIGATÓRIO no topo de qualquer página que use sessões.
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once 'includes/db.php';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        header('Location: login.php?error=empty');
        exit();
    }

    // Busca o usuário no banco de dados pelo e-mail
    $sql = "SELECT id, nome, senha, tipo_usuario FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    
    $usuario = $stmt->fetch();

    // Verifica se o usuário foi encontrado E se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Senha correta! Login bem-sucedido.
        
        // Armazena os dados do usuário na sessão
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        $_SESSION['user_type'] = $usuario['tipo_usuario'];
        
        // Redireciona para uma página de "dashboard" ou painel do usuário
        header('Location: dashboard.php');
        exit();

    } else {
        // Usuário não encontrado ou senha incorreta
        header('Location: login.php?error=invalid');
        exit();
    }

} else {
    // Se o script for acessado diretamente, redireciona para a home
    header('Location: index.php');
    exit();
}
?>