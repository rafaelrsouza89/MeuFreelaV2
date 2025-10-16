<?php
session_start();
require_once 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    
    // CORREÇÃO: Garante que 'tipo_usuario' seja selecionado
    $sql = "SELECT id, nome, senha, tipo_usuario FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        
        // CORREÇÃO CRÍTICA: Define a variável de sessão 'user_type'
        $_SESSION['user_type'] = $usuario['tipo_usuario']; 
        
        header('Location: dashboard.php'); 
        exit();
    } else {
        header('Location: login.php?error=invalid'); 
        exit();
    }
}
?>
