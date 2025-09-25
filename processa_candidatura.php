<?php
session_start();
require_once 'includes/db.php';

// 1. Validação de segurança: usuário logado e com permissão
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'freelancer' && $_SESSION['user_type'] !== 'ambos')) {
    // Se não tiver permissão, redireciona para o login
    header('Location: login.php');
    exit();
}

// 2. Verifica se a requisição é do tipo POST e se o ID da vaga foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaga_id'])) {
    
    $id_vaga = $_POST['vaga_id'];
    $id_usuario = $_SESSION['user_id'];
    
    try {
        // 3. VERIFICA SE JÁ EXISTE UMA CANDIDATURA para evitar duplicatas
        $sql_check = "SELECT id FROM candidatura WHERE id_usuario = :id_usuario AND vaga_id = :id_vaga";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute(['id_usuario' => $id_usuario, 'id_vaga' => $id_vaga]);
        
        if ($stmt_check->fetch()) {
            // Se encontrou, o usuário já se candidatou. Redireciona com aviso.
            header('Location: detalhes_vaga.php?id=' . $id_vaga . '&status=already_applied');
            exit();
        }
        
        // 4. Se não houver duplicata, INSERE a nova candidatura
        $sql_insert = "INSERT INTO candidatura (id_usuario, vaga_id, data_candidatura, status) 
                       VALUES (:id_usuario, :id_vaga, NOW(), :status)";
                       
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            'id_usuario' => $id_usuario,
            'id_vaga' => $id_vaga,
            'status' => 'enviada' // Status inicial da candidatura
        ]);
        
        // 5. Redireciona de volta para a página da vaga com mensagem de sucesso
        header('Location: detalhes_vaga.php?id=' . $id_vaga . '&status=success');
        exit();

    } catch (PDOException $e) {
        // Em caso de erro no banco de dados
        // error_log($e->getMessage());
        header('Location: detalhes_vaga.php?id=' . $id_vaga . '&status=dberror');
        exit();
    }
    
} else {
    // Se o acesso não for via POST, redireciona para a página de vagas
    header('Location: procurar_vagas.php');
    exit();
}
?>