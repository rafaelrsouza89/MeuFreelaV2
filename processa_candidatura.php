<?php
session_start();
require_once 'includes/db.php';

// Redireciona se a requisição não for POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: procurar_vagas.php');
    exit();
}

// Redireciona para o login se o usuário não estiver logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtém o ID da vaga enviado pelo formulário
$vaga_id = isset($_POST['vaga_id']) ? (int) $_POST['vaga_id'] : 0;

// Redireciona se o ID da vaga for inválido
if ($vaga_id <= 0) {
    header('Location: detalhes_vaga.php?id=' . $vaga_id . '&msg=erro');
    exit();
}

// verifica tipo na sessão (aceita user_type ou tipo_usuario)
$tipo = $_SESSION['user_type'] ?? $_SESSION['tipo_usuario'] ?? '';

// Redireciona se o tipo de usuário não tiver permissão
if (!in_array(strtolower($tipo), ['freelancer','freelance','ambos'])) {
    header('Location: detalhes_vaga.php?id=' . $vaga_id . '&msg=erro');
    exit();
}

$id_usuario = (int) $_SESSION['user_id'];

try {
    // 1. Verifica se a vaga existe
    $stmt = $pdo->prepare("SELECT id FROM vaga WHERE id = ?");
    $stmt->execute([$vaga_id]);
    
    // Redireciona se a vaga não existir
    if (!$stmt->fetch()) {
        header('Location: detalhes_vaga.php?id=' . $vaga_id . '&msg=erro');
        exit();
    }

    // 2. Verifica candidatura duplicada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidatura WHERE id_usuario = ? AND vaga_id = ?");
    $stmt->execute([$id_usuario, $vaga_id]);
    
    // Redireciona se já houver uma candidatura para esta vaga
    if ((int)$stmt->fetchColumn() > 0) {
        header('Location: detalhes_vaga.php?id=' . $vaga_id . '&msg=ja_aplicou');
        exit();
    }

    // 3. Insere nova candidatura
    $stmt = $pdo->prepare("INSERT INTO candidatura (id_usuario, vaga_id, data_candidatura, status) VALUES (?, ?, NOW(), 'pendente')");
    $stmt->execute([$id_usuario, $vaga_id]);

    // Redireciona com mensagem de sucesso
    header('Location: detalhes_vaga.php?id=' . $vaga_id . '&msg=aplicado');
    exit();

} catch (PDOException $e) {
    // Em caso de erro, redireciona com mensagem de erro
    header('Location: detalhes_vaga.php?id=' . $vaga_id . '&msg=erro');
    exit();
}
?>