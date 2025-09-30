<?php
session_start();
require_once 'includes/db.php';

// 1. Segurança: Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['user_id'];
$message = ''; // Variável para exibir mensagens de sucesso ou erro

// 2. Processamento do formulário (quando ele for enviado via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta dos dados gerais
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    
    // Coleta dos dados de freelancer (se existirem no formulário)
    $biografia = isset($_POST['biografia']) ? trim($_POST['biografia']) : null;
    $especialidades = isset($_POST['especialidades']) ? trim($_POST['especialidades']) : null;
    $portfolio_url = isset($_POST['portfolio_url']) ? trim($_POST['portfolio_url']) : null;
    
    // Validação simples
    if (empty($nome) || empty($telefone)) {
        $message = '<div class="alert alert-danger">Nome e telefone são obrigatórios.</div>';
    } else {
        try {
            // Query para ATUALIZAR os dados do usuário
            $sql_update = "UPDATE usuario SET 
                                nome = :nome, 
                                telefone = :telefone,
                                biografia = :biografia,
                                especialidades = :especialidades,
                                portfolio_url = :portfolio_url
                           WHERE id = :id_usuario";

            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                'nome' => $nome, 
                'telefone' => $telefone,
                'biografia' => $biografia,
                'especialidades' => $especialidades,
                'portfolio_url' => $portfolio_url,
                'id_usuario' => $id_usuario
            ]);
            
            // Atualiza o nome na sessão para que a mudança apareça imediatamente no site
            $_SESSION['user_name'] = $nome;
            
            $message = '<div class="alert alert-success">Perfil atualizado com sucesso!</div>';
            
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Erro ao atualizar o perfil. Tente novamente.</div>';
        }
    }
}

// 3. Busca os dados atuais do usuário para preencher o formulário
try {
    $sql_select = "SELECT nome, email, telefone, biografia, especialidades, portfolio_url