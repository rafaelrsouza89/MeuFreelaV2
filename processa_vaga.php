<?php
session_start();
require_once 'includes/db.php';

// 1. VERIFICAÇÃO DE PERMISSÃO E LOGIN
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'contratante' && $_SESSION['user_type'] !== 'ambos')) {
    // Se não estiver logado ou não tiver permissão de contratante/ambos
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $id_usuario = $_SESSION['user_id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $tipo_vaga = $_POST['tipo_vaga'];
    $remuneracao = $_POST['remuneracao'] ?: 0.00; 
    $local = trim($_POST['local']);
    $data_limite = $_POST['data_limite'];

    // 2. VALIDAÇÃO DE CAMPOS
    if (empty($titulo) || empty($descricao) || empty($local) || empty($data_limite)) {
        header('Location: publicar_vaga.php?error=emptyfields'); 
        exit();
    }

    try {
        $sql = "INSERT INTO vaga (id_usuario, titulo, descricao, tipo_vaga, remuneracao, local, data_publicacao, data_limite) 
                VALUES (:id_usuario, :titulo, :descricao, :tipo_vaga, :remuneracao, :local, NOW(), :data_limite)";
        
        $stmt = $pdo->prepare($sql);
        
        // EXECUTA A INSERÇÃO
        $success = $stmt->execute([
            'id_usuario' => $id_usuario,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'tipo_vaga' => $tipo_vaga,
            'remuneracao' => $remuneracao,
            'local' => $local,
            'data_limite' => $data_limite
        ]);

        // 3. REDIRECIONAMENTO DE SUCESSO: Redireciona para a lista de Minhas Vagas
        if ($success) {
            // Agora redireciona para a lista de vagas do contratante
            header('Location: minhas_vagas.php?status=vaga_publicada'); 
            exit();
        } else {
            header('Location: publicar_vaga.php?error=dberror_execution');
            exit();
        }

    } catch (PDOException $e) {
        // 4. REDIRECIONAMENTO DE ERRO: Volta para o formulário com a mensagem de erro
        error_log("PDO ERROR: " . $e->getMessage()); // Registra o erro para debug
        header('Location: publicar_vaga.php?error=dberror_pdo');
        exit();
    }
} else {
    // Se o acesso for direto sem POST
    header('Location: index.php');
    exit();
}
?>
