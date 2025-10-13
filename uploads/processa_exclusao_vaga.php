<?php
session_start();
require_once 'includes/db.php';

// Verificação de segurança: usuário logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_contratante = $_SESSION['user_id'];
    $vaga_id = filter_input(INPUT_POST, 'vaga_id', FILTER_VALIDATE_INT);

    if (!$vaga_id) {
        header('Location: minhas_vagas.php?status=error&msg=id_invalido');
        exit();
    }

    try {
        // EXCLUI as candidaturas primeiro (devido à chave estrangeira) - Opcional se usar ON DELETE CASCADE no DB
        $sql_delete_candidaturas = "DELETE FROM candidatura WHERE vaga_id = :vaga_id";
        $stmt_candidaturas = $pdo->prepare($sql_delete_candidaturas);
        $stmt_candidaturas->execute(['vaga_id' => $vaga_id]);

        // EXCLUI a vaga: A condição WHERE garante que SÓ O PROPRIETÁRIO possa excluir a vaga.
        $sql_delete_vaga = "DELETE FROM vaga WHERE id = :vaga_id AND id_usuario = :id_contratante";
        $stmt_vaga = $pdo->prepare($sql_delete_vaga);
        $stmt_vaga->execute([
            'vaga_id' => $vaga_id,
            'id_contratante' => $id_contratante
        ]);
        
        // Redireciona com sucesso
        header('Location: minhas_vagas.php?status=success&msg=vaga_excluida');
        exit();

    } catch (PDOException $e) {
        // Redireciona com erro em caso de falha no banco
        header('Location: minhas_vagas.php?status=error&msg=dberror_exclusao');
        exit();
    }
} else {
    // Se o acesso for direto sem POST, redireciona
    header('Location: minhas_vagas.php');
    exit();
}
?>
