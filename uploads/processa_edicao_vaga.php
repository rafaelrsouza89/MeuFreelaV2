<?php
session_start();
require_once 'includes/db.php';

// Verificação de segurança: usuário logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_contratante = $_SESSION['user_id'];
    $vaga_id = filter_input(INPUT_POST, 'vaga_id', FILTER_VALIDATE_INT);

    // Coleta e sanitiza os dados
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $tipo_vaga = $_POST['tipo_vaga'];
    $remuneracao = $_POST['remuneracao'] ?: 0.00;
    $local = trim($_POST['local']);
    $data_limite = $_POST['data_limite'];

    // Validação básica
    if (!$vaga_id || empty($titulo) || empty($descricao) || empty($local) || empty($data_limite)) {
        header('Location: minhas_vagas.php?status=error&msg=campos_invalidos');
        exit();
    }

    try {
        // SQL para UPDATE: A condição WHERE garante que SÓ O PROPRIETÁRIO possa editar a vaga.
        $sql = "UPDATE vaga SET titulo = :titulo, descricao = :descricao, tipo_vaga = :tipo_vaga, remuneracao = :remuneracao, local = :local, data_limite = :data_limite WHERE id = :vaga_id AND id_usuario = :id_contratante";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'titulo' => $titulo,
            'descricao' => $descricao,
            'tipo_vaga' => $tipo_vaga,
            'remuneracao' => $remuneracao,
            'local' => $local,
            'data_limite' => $data_limite,
            'vaga_id' => $vaga_id,
            'id_contratante' => $id_contratante
        ]);
        
        // Se a atualização foi bem-sucedida (e count() > 0), redireciona com sucesso
        header('Location: minhas_vagas.php?status=success&msg=vaga_atualizada');
        exit();

    } catch (PDOException $e) {
        // Redireciona com erro em caso de falha no banco
        header('Location: editar_vaga.php?id=' . $vaga_id . '&status=error&msg=dberror');
        exit();
    }
} else {
    // Se o acesso for direto sem POST, redireciona
    header('Location: minhas_vagas.php');
    exit();
}
?>
