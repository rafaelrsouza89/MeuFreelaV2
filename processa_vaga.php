<?php
session_start();
require_once 'includes/db.php';

// Novamente, verificamos a sessão e a permissão do usuário
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'contratante' && $_SESSION['user_type'] !== 'ambos')) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $id_usuario = $_SESSION['user_id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $tipo_vaga = $_POST['tipo_vaga'];
    $remuneracao = $_POST['remuneracao'] ?: 0.00; // Se vazio, assume 0.00
    $local = trim($_POST['local']);
    $data_limite = $_POST['data_limite'];

    // Validação simples (pode ser expandida conforme a necessidade)
    if (empty($titulo) || empty($descricao) || empty($local) || empty($data_limite)) {
        header('Location: publicar_vaga.php?error=emptyfields');
        exit();
    }

    try {
        $sql = "INSERT INTO vaga (id_usuario, titulo, descricao, tipo_vaga, remuneracao, local, data_publicacao, data_limite) 
                VALUES (:id_usuario, :titulo, :descricao, :tipo_vaga, :remuneracao, :local, NOW(), :data_limite)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'id_usuario' => $id_usuario,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'tipo_vaga' => $tipo_vaga,
            'remuneracao' => $remuneracao,
            'local' => $local,
            'data_limite' => $data_limite
        ]);

        // Redireciona para o dashboard com mensagem de sucesso
        header('Location: dashboard.php?status=vaga_publicada');
        exit();

    } catch (PDOException $e) {
        // Log do erro (ideal para produção)
        // error_log($e->getMessage());
        header('Location: publicar_vaga.php?error=dberror');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>