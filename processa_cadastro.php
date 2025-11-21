<?php
require_once 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $telefone = trim($_POST['telefone']);
    $tipo_usuario = $_POST['tipo_usuario'];
    
    if (empty($nome) || empty($email) || empty($senha) || empty($telefone) || empty($tipo_usuario)) { exit('Preencha todos os campos.'); }
    
    $sql_check = "SELECT id FROM usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['email' => $email]);
    if ($stmt_check->fetch()) { exit('E-mail já cadastrado.'); }
    
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    try {
        $sql_insert = "INSERT INTO usuario (nome, email, senha, telefone, tipo_usuario, data_cadastro) VALUES (:nome, :email, :senha, :telefone, :tipo_usuario, NOW())";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha_hash, 'telefone' => $telefone, 'tipo_usuario' => $tipo_usuario]);
        
        // Destruir qualquer sessão ativa.
        // Isso garante que, se o usuário estava logado como outro antes de se cadastrar,
        // a sessão antiga seja limpa para que o novo usuário faça o login corretamente.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset(); // Remove as variáveis de sessão
        session_destroy(); // Destrói a sessão
        
        header('Location: login.php?status=success'); 
        exit();
    } catch (PDOException $e) { 
        header('Location: cadastro.php?status=error'); 
        exit(); 
    }
}
?>