<?php
session_start();
// O arquivo gerenciar_perfil.php foi descontinuado
// e a funcionalidade de edição foi consolidada em dashboard.php.
// Redireciona o usuário para o dashboard.

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

header('Location: dashboard.php');
exit();
?>